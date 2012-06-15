<?php 
namespace Phm\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Phm\Tools\NamespaceAnalyzer\NamespaceAnalyzer;

class AnalyzeCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('analyze')
            ->setDescription('This tool will check if all used (T_USE) namespaces are needed in a given PHP file.')
            ->addArgument('file', InputArgument::REQUIRED, 'File to analyze')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
		echo $input->getArgument('file');
		
        $analyzer = new NamespaceAnalyzer(token_get_all(file_get_contents($input->getArgument('file'))), true);

		$unusedNamespaces = $analyzer->getUnusedNamespaces();
		
		var_dump($unusedNamespaces);

		foreach ($unusedNamespaces as $namespace) {
		    $output->writeln($filename . ": Namespace '" . $namespace . "' is not used.");
		}
    }
}