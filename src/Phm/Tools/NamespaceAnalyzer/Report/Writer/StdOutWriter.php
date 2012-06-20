<?php
namespace Phm\Tools\NamespaceAnalyzer\Report\Writer;

class StdOutWriter
{
    public function write ($formatteedString)
    {
        echo $formatteedString;
    }
}