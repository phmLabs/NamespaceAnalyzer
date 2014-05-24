<?php
namespace Phm\Command;
use Phm\Tools\NamespaceAnalyzer\Report\Writer\FileWriter;
use Phm\Tools\NamespaceAnalyzer\Report\Format\XUnitFormat;
use Phm\Tools\NamespaceAnalyzer\Report\Format\SimpleTextFormat;
use Phm\Tools\NamespaceAnalyzer\Report\Writer\StdOutWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phm\Tools\NamespaceAnalyzer\NamespaceAnalyzer;
use Symfony\Component\Finder\Finder;

class AnalyzeCommand extends Command
{

    protected function configure ()
    {
        $this->setName('analyze')
            ->setDescription(
                'This tool will check if all used (T_USE) namespaces are needed in a given PHP file.')
            ->addArgument('file', InputArgument::REQUIRED,
                'File or directory to analyze')
            ->addOption('checkDocBlock', null, InputOption::VALUE_OPTIONAL,
                'If set, docblocks will checked too. Defaults to true', true)
            ->addOption('format', null, InputOption::VALUE_OPTIONAL, 'Format',
                'standard')
            ->addOption('outputfile', null, InputOption::VALUE_OPTIONAL,
                'Output');
    }

    protected function execute (InputInterface $input, OutputInterface $output)
    {
        $unusedNamespaces = array();

        $filename = $input->getArgument('file');
        if (is_dir($filename)) {
            $finder = new Finder();
            $finder->files()
                ->in($filename)
                ->name('*.php');

            foreach ($finder as $file) {

                $filename = $file->getRealpath();
                $analyzer = new NamespaceAnalyzer(
                        token_get_all(file_get_contents($filename)),
                        $input->getOption('checkDocBlock'));
                $unusedNamespaces[$file->getRealpath()] = $analyzer->getUnusedNamespaces();
            }
        } else {
            $analyzer = new NamespaceAnalyzer(
                    token_get_all(file_get_contents($filename)),
                    $input->getOption('checkDocBlock'));
            $unusedNamespaces[$filename] = $analyzer->getUnusedNamespaces();
        }
        $this->createReport($input, $output, $unusedNamespaces);

        return 0;
    }

    private function createReport (InputInterface $input,
            OutputInterface $output, $unusedNamespaces)
    {
        $outputFormat = $input->getOption("format");

        switch ($outputFormat) {
            case "xunit":
                $format = new XUnitFormat();
                if (is_null($input->getOption("outputfile"))) {
                    $output->write(
                            "<error>When chosing XUnit as output format you have to set an outputfile.</error>\n");
                    die(1);
                }
                $writer = new FileWriter($input->getOption("outputfile"));
                break;
            case "standard":
                $format = new SimpleTextFormat();
                $writer = new StdOutWriter($output);
                break;
            default:
                $output->write(
                        "<error>Unknown format '" . $outputFormat . "'</error>\n");
                die(1);
        }

        $writer->write($format->getFormattedReport($unusedNamespaces));
    }
}