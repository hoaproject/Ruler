<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalAnd
 *
 * @uses AbstractLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalAnd extends AbstractLogicalOperator implements LogicalOperatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return 'AND';
    }

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        foreach ($this->getConditions() as $condition) {
            if (!$condition->assert()) {
                return false;
            }
        }

        return true;
    }
}
