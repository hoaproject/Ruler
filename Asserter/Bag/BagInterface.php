<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Asserter\Bag {

/**
 * BagInterface
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
interface BagInterface {

    /**
     * @return string
     */
    public function __toString ( );

    /**
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context );

    /**
     * @return mixed
     */
    public function getValue ( );
}

}
