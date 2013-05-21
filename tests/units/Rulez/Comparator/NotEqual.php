<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class NotEqual extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\NotEqual';

        return array(
            array($comparator, 'foo', 'foo', false),
            array($comparator, 'foo', 'bar', true),
            array($comparator, 1, 1, false),
            array($comparator, 1, "1", false),
            array($comparator, true, "1", false),
            array($comparator, 0, "false", false),
        );
    }
}
