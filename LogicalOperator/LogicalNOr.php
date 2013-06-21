<?php

namespace Hoa\Ruler\LogicalOperator {

/**
 * LogicalNOr
 *
 * @uses LogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalNOr extends LogicalOperator implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'NOR';
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
