<?php

include_once __DIR__ . '/../Phm/Tools/NamespaceAnalyzer/NamespaceAnalyzer.php';

$filename = $_SERVER["argv"][1];
$analyzer = new Phm\Tools\NamespaceAnalyzer\NamespaceAnalyzer(token_get_all(file_get_contents($filename)), true);

$unusedNamespaces = $analyzer->getUnusedNamespaces();

foreach ($unusedNamespaces as $namespace) {
    echo $filename . ": Namespace '" . $namespace . "' is not used.\n";
}