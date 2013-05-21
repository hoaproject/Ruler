<?php

namespace tests\units\Rulez\Comparator;

use mageekguy\atoum;

require_once __DIR__."/../../../../vendor/autoload.php";
require_once __DIR__."/AbstractComparator.php";

class Is extends AbstractComparator
{
    public function dataProviderAssert()
    {
        $comparator = '\Rulez\Comparator\Is';

        return array(
            array($comparator, true, true, true),
            array($comparator, true, false, false),
            array($comparator, null, null, true),
            array($comparator, 'any', null, false),
        );
    }
}
