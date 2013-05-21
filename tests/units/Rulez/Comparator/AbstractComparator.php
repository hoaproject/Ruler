<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";

abstract class AbstractComparator extends atoum\test
{
    /**
     * @dataProvider dataProviderAssert
     */
    public function testAssert($comparator, $left, $right, $resultExpected)
    {
        $comparator = new $comparator($left, $right);

        $this->boolean($comparator->assert())
            ->isEqualTo($resultExpected);
    }
}
