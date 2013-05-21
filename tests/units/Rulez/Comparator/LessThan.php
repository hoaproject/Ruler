<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class LessThan extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\LessThan';

        return array(
            array($comparator, 1, 2, true),
            array($comparator, '1', '2', true),
            array($comparator, 2, 1, false),
            array($comparator, '2', '1', false),
            array($comparator, 2, 2, false),
        );
    }
}
