<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalOr
 *
 * @uses LogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalOr extends LogicalOperator implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'OR';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        foreach ($this->getConditions() as $condition) {
            if ($condition->assert()) {
                return true;
            }
        }

        return false;
    }
}
