<?php

namespace Hoa\Ruler\Model\Operator {

/**
 * LogicalAnd
 *
 * @uses Operator
 * @uses LogicalInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class LogicalAnd extends Operator implements LogicalInterface {

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
