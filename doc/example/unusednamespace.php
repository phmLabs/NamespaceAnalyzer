<?php

// used namespaces
use Phm\Test\ExtendedClass;
use Phm\Test\StaticClassWithDoubleQuotes;
use Phm\Test\StaticClassWithSingleQuotes;
use Phm\Test\InstanceOfClass;
use Phm\Test\NewClass;
use Phm\Test;
use Phm\TestAlias as AliasNamespace;

// unused namespaces
use Test\UnusedNamespaceClass;
use Testt;

class MyTest extends ExtendedClass
{
    /**
     * @param TypeHintClass $typeHintedClass mit text hintendran
     * @param SecondArgumentClass $secondArgument
     *
     * @throws Exception
     * @return ReturnValueClass
     */
    public function doTest (TypeHintClass $typeHintedClass, $secondArgument)
    {
        StaticClassWithDoubleQuotes::TEST = "test1";
        StaticClassWithSingleQuotes::TEST = 'test2';

        $test instanceof InstanceOfClass;

        $newTest = new NewClass;

        new AliasNamespace;
        new Test\NewClass;

        try {
            new NamesspacedNewClass();
            new NamespacesNewClassWithBrackets();
        } catch (Exception $e) {
            throw $e;
        }
    }
}