<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalNot
 *
 * @uses AbstractLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalNot extends LogicalAnd implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'NOT';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return !parent::assert();
    }
}
