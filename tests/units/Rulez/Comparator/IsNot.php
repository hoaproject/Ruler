<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class IsNot extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\IsNot';

        return array(
            array($comparator, true, true, false),
            array($comparator, true, false, true),
            array($comparator, null, null, false),
            array($comparator, 'any', null, true),
        );
    }
}
