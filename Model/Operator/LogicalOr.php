<?php

namespace Hoa\Ruler\Model\Operator {

/**
 * LogicalOr
 *
 * @uses Operator
 * @uses LogicalInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class LogicalOr extends Operator implements LogicalInterface {

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

}
