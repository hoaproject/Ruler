<?php

namespace Hoa\Ruler\LogicalOperator {

/**
 * LogicalAnd
 *
 * @uses LogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalAnd extends LogicalOperator implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'AND';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        foreach ($this->getConditions() as $condition) {
            if (!$condition->assert()) {
                return false;
            }
        }

        return true;
    }
}

}
