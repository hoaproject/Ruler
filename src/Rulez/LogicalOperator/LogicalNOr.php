<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalNOr
 *
 * @uses AbstractLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalNOr extends AbstractLogicalOperator implements LogicalOperatorInterface {

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
