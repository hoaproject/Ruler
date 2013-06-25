<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Model\Operator {

/**
 * LogicalInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface LogicalInterface {

    /**
     * @param Context $context context
     *
     * @return boolean
     */
    public function assert ( );

    /**
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context );

    /**
     * @return string
     */
    public function getToken ( );
}

}
