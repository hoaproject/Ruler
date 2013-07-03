<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Bag\BagInterface
 */
-> import('Ruler.Asserter.Bag.BagInterface');

}

namespace Hoa\Ruler\Model\Operator {

/**
 * LogicalNot
 *
 * @uses UnaryOperator
 * @uses LogicalInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class LogicalNot extends UnaryOperator implements LogicalInterface {

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
