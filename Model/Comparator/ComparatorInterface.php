<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Model\Comparator {

/**
 * ComparatorInterface
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
interface ComparatorInterface {

    /**
     * @throws InvalidValueException
     *
     * @return boolean
     */
    public function assert ( );

    /**
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform( \Hoa\Ruler\Asserter\Context $context );

    /**
     * @return string
     */
    public function getToken ( );
}

}
