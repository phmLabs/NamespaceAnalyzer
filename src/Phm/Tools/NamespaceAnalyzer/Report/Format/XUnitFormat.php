<?php
namespace Phm\Tools\NamespaceAnalyzer\Report\Format;

class XUnitFormat
{

    private $errorArray;

    public function getFormattedReport (array $errorArray)
    {
        $xUnitDom = new \DOMDocument('1.0', 'utf-8');
        $xml_testsuites = $xUnitDom->createElement('testsuites');
        $xUnitDom->appendChild($xml_testsuites);
        $xml_testsuite = $xUnitDom->createElement('testsuite');
        $num_failed = 0;

        foreach ($errorArray as $filename => $namespaces) {
            foreach ($namespaces as $namespace) {
                $num_failed ++;
                $xml_testcase = $xUnitDom->createElement('testcase');
                $xml_testcase->setAttribute('file', $filename);

                $xml_testcase->setAttribute('name',
                        'Namespace Analyzer - ' . $namespace);

                $xml_failure = $xUnitDom->createElement('failure');
                $xml_failure->setAttribute('message',
                        $namespace . " was included (T_USE) but not used.");
                $xml_testcase->appendChild($xml_failure);
                $xml_testsuite->appendChild($xml_testcase);
            }
        }

        $xml_testsuite->setAttribute('name', 'Namespace Analyzer');
        $xml_testsuite->setAttribute('errors', 0);
        $xml_testsuite->setAttribute('failures', $num_failed);
        $xml_testsuite->setAttribute('tests', $num_failed);
        $xml_testsuite->setAttribute('timestamp', strftime("%Y-%m-%dT%H:%M:%S"));
        $xml_testsuites->appendChild($xml_testsuite);
        $xUnitDom->formatOutput = true;
        return $xUnitDom->saveXML();
    }
}