<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalXNOr
 *
 * @uses LogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalXNOr extends LogicalOperator implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'XNOR';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

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
