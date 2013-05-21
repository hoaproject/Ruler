<?php

namespace tests\units\Rulez\LogicalOperator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";

abstract class AbstractLogicalOperator extends atoum\test
{
    /**
     * @dataProvider dataProviderAssert
     */
    public function testAssert($operator, array $entries, $resultExpected)
    {
        $conditions = array();

        foreach ($entries as $entry) {
            $condition = new \mock\Rulez\LogicalOperator\LogicalOperatorInterface();
            $condition->getMockController()->assert = $entry;

            $conditions[] = $condition;
        }

        $operator = new $operator($conditions);

        $this->boolean($operator->assert())
            ->isEqualTo($resultExpected);
    }
}
