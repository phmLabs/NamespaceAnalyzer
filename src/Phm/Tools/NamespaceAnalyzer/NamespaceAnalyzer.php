<?php

namespace Phm\Tools\NamespaceAnalyzer;

/**
 * This class can be used to process a list of tokens and tell which use
 * statement is
 * not used.
 *
 * @author Nils Langner <nils.langner@phmlabs.com>
 */
class NamespaceAnalyzer
{
    /**
     * Array of tokens that has to be processed
     *
     * @var array
     */
    private $tokens = array();

    /**
     * Array of unused namespaces.
     * Is filled after process() was called.
     *
     * @var string[]
     */
    private $unusedNamespaces = array();

    /**
     * Internal state of the class.
     * True if the tokens where processed.
     *
     * @var boolean
     */
    private $isProcessed = false;

    /**
     * Option that defines if doc block comments are processed.
     *
     * @var boolean
     */
    private $isCheckDocBlocked;

    private $nameSpaceTokenNumber = array();

    /**
     * @param array $tokens array of tokens that are processed
     * @param boolean $checkDocBlock if the parameter is false doc blocks are not processed.
     */
    public function __construct (array $tokens, $checkDocBlock = false)
    {
        $this->isCheckDocBlocked = $checkDocBlock;
        $this->tokens = $tokens;
    }

    /**
     * Processing the tokens and set the unsued namespaces.
     */
    private function processTokens ()
    {
        if (! $this->isProcessed) {
            $useNamespaces = array();

            for ($stackPtr = 0; $stackPtr < count($this->tokens); $stackPtr ++) {
                $token = $this->tokens[$stackPtr];
                if ($token[0] == T_USE && $this->tokens[$stackPtr - 2] != ")") {
                    $useNamespaces[$this->getNamespace($stackPtr)] = false;
                } elseif ($token[0] == T_STRING) {
                    if ($this->isNamespaced($stackPtr, $useNamespaces)) {
                        $namespace = $this->tokens[$stackPtr][1];
                        if (array_key_exists($namespace, $useNamespaces)) {
                            $useNamespaces[$namespace] = true;
                        }
                    }
                } elseif ($this->isCheckDocBlocked) {
                    if ($token[0] == T_DOC_COMMENT || $token[0] == T_COMMENT) {
                        $namespaces = $this->getTypeHintDocComments($token[1]);
                        foreach ($namespaces as $namespace) {
                            if (array_key_exists($namespace, $useNamespaces)) {
                                $useNamespaces[$namespace] = true;
                            }
                        }
                    }
                }
            }
            foreach ($useNamespaces as $nameSpace => $isUsed) {
                if (! $isUsed) {
                    $this->unusedNamespaces[] = $nameSpace;
                }
            }
            $this->unusedNamespaces = array_unique($this->unusedNamespaces);
            $this->isProcessed = true;
        }
    }

    /**
     * Checks if a given token is a namespace
     *
     * @param int $stackPtr integer that is pointing to the analyzed token in token array
     * @param array $useNamespaces array of the available Namespaces in a file
     *
     * @return boolean returns true if the given element is a namespace
     */
    private function isNamespaced ($stackPtr, $currentNamespaces = array())
    {
        if ($this->tokens[$stackPtr - 2][0] == T_NEW) {
            return true;
        }
        if ($this->tokens[$stackPtr + 2][0] == T_VARIABLE) {
            return true;
        }
        if ($this->tokens[$stackPtr + 1][0] == T_PAAMAYIM_NEKUDOTAYIM) {
            return true;
        }
        if ($this->tokens[$stackPtr - 2][0] == T_EXTENDS) {
            return true;
        }
        if ($this->tokens[$stackPtr - 2][0] == T_IMPLEMENTS) {
            return true;
        }
        if ($this->tokens[$stackPtr - 2][0] == T_INSTANCEOF) {
            return true;
        }

        if ((!array_key_exists($stackPtr, $this->nameSpaceTokenNumber)) &&
            (array_key_exists($this->tokens[$stackPtr][1], $currentNamespaces)))
        {
            return true;
        }

        return false;
    }

    /**
     * Takes a comment block a string and returns all found namespaces.
     *
     * @param string $comment
     *
     * @return string[] found namespaces
     */
    private function getTypeHintDocComments ($comment)
    {
        $regEx = "^@(param|return|var|throws|see)\s+(\S+)\s^";
        preg_match_all($regEx, $comment, $matches);

        return $matches[2];
    }

    /**
     * Returns the namespace alias for a given T_USE token.
     *
     * @param int $stackPtr integer that is pointing to the analyzed token in token array
     *
     * @return string the namespace alias
     */
    private function getNamespace ($stackPtr)
    {
        $ptr = $stackPtr + 2;
        while ($this->tokens[$ptr] != ";") {
            $lastNamespacePart = $this->tokens[$ptr][1];
            $ptr ++;
        }
        $this->nameSpaceTokenNumber[$ptr -1] = true;
        return $lastNamespacePart;
    }

    /**
     * Returns a list of namespaces that are included (T_USE) but not used.
     *
     * @return string[] array of unsued namespaces.
     */
    public function getUnusedNamespaces ()
    {
        $this->processTokens();
        return $this->unusedNamespaces;
    }
}