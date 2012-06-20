<?php
namespace Phm\Tools\NamespaceAnalyzer\Report\Format;

class SimpleTextFormat
{
    public function getFormattedReport (array $errorArray)
    {
        $text = "";
        foreach ($errorArray as $filename => $namespaces) {
            foreach ($namespaces as $namespace) {
                $text .= $filename . ": " . $namespace . " was included (T_USE) but not used.\n";
            }
        }

        return $text;
    }
}