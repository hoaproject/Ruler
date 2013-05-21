<?php

namespace tests\units\Rulez\LogicalOperator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractLogicalOperator.php";

class LogicalXOr extends AbstractLogicalOperator
{
    public function dataProviderAssert()
    {
        $operator = '\Rulez\LogicalOperator\LogicalXOr';

        return array(
            array($operator, array(0, 0), false),
            array($operator, array(0, 1), true),
            array($operator, array(1, 0), true),
            array($operator, array(1, 1), false),
            array($operator, array(1, 1, 1), true),
            array($operator, array(1, 0, 1), false),
            array($operator, array(0, 0, 1), true),
            array($operator, array(0, 0, 0), false),
        );
    }
}
