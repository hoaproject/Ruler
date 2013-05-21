<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class LessThanEqual extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\LessThanEqual';

        return array(
            array($comparator, 1, 2, true),
            array($comparator, '1', '2', true),
            array($comparator, 2, 1, false),
            array($comparator, '2', '1', false),
            array($comparator, 2, 2, true),
        );
    }
}
