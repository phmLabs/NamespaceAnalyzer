<?php
namespace Phm\Tools\NamespaceAnalyzer\Report\Writer;

use Symfony\Component\Console\Output\OutputInterface;

class StdOutWriter
{
    private $output;

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    public function write ($formatteedString)
    {
        $this->output->write($formatteedString);
    }
}