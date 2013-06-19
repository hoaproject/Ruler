<?php

namespace Rulez\LogicalOperator;

use Rulez\Asserter\Bag\BagInterface;

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

        if ($this->condition instanceof BagInterface) {
            return ! $this->condition->getValue();
        } else {
            return ! $this->condition->assert();
        }
    }
}
