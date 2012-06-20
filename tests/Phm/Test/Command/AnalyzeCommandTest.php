<?php
namespace Phm\Tests\Command;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Tester\CommandTester;

use Phm\Command\AnalyzeCommand;

class AnalyzeCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $command = new AnalyzeCommand();
        $tester = new CommandTester($command);

        $code = $tester->execute(array('file' => __DIR__ . '/../../../../doc/example/unusednamespace.php'));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }
}