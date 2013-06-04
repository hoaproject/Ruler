<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class In extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\In';

        return array(
            // not an array
            array($comparator, 1, true, false),
            array($comparator, 'foo', array('foo', 'bar'), true),
            array($comparator, 'baz', array('foo', 'bar'), false),
            array($comparator, 1, array('1', '2'), true),
        );
    }
}
