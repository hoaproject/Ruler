<?php

namespace Hoa\Ruler\Model\Operator {

/**
 * LogicalXOr
 *
 * @uses Operator
 * @uses LogicalInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalXOr extends Operator implements LogicalInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'XOR';
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
            } else {
                $left = $left ^ $value;
            }
        }

        return (bool) $left;
    }
}

}
