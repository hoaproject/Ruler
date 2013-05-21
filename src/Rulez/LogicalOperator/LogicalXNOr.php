<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalXNOr
 *
 * @uses AbstractLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalXNOr extends AbstractLogicalOperator implements LogicalOperatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return 'XNOR';
    }

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        $left = null;

        foreach ($this->getConditions() as $condition) {
            $value = $condition->assert();

            if (null === $left) {
                $left = $value;
            } elseif ($left != $value) {
                return false;
            }
        }

        return true;
    }
}
