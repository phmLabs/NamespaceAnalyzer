<?php

use Test\Test;
use Test2\Test2 as SecondTest;

use Test\ExtendedClass;
use Test\AnyClass;
use Test\SecondArgumentClass;

class MyTest2 extends ExtendedClass
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

     	$newtest = new SecondTest;

     	$newnewTest = new Test\MyTest;
     	$newnewTest = new Test\ThirdTest;

        try {
            new NamesspacedNewClass();
            new NamespacesNewClassWithBrackets();
        } catch (Exception $e) {
            throw $e;
        }
    }
}