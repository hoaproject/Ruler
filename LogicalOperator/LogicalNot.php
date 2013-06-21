<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Bag\BagInterface
 */
-> import('Ruler.Asserter.Bag.BagInterface');

}

namespace Hoa\Ruler\LogicalOperator {

/**
 * LogicalNot
 *
 * @uses UnaryLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalNot extends UnaryLogicalOperator implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'NOT';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        if ($this->condition instanceof \Hoa\Ruler\Asserter\Bag\BagInterface) {
            return ! $this->condition->getValue();
        } else {
            return ! $this->condition->assert();
        }
    }
}

}
