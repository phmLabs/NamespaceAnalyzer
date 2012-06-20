<?php
namespace Phm\Tools\NamespaceAnalyzer\Report\Writer;

class FileWriter
{

    private $filename;

    public function __construct ($filename)
    {
        $this->filename = $filename;
    }

    public function write ($formatteedString)
    {
        file_put_contents($this->filename, $formatteedString);
    }
}