<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class Equal extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\Equal';

        return array(
            array($comparator, 'foo', 'foo', true),
            array($comparator, 'foo', 'bar', false),
            array($comparator, 1, 1, true),
            array($comparator, 1, "1", true),
            array($comparator, true, "1", true),
            array($comparator, 0, "false", true),
        );
    }
}
