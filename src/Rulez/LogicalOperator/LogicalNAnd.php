<?php

namespace Rulez\LogicalOperator;

/**
 * LogicalNAnd
 *
 * @uses AbstractLogicalOperator
 * @uses LogicalOperatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LogicalNAnd extends LogicalAnd implements LogicalOperatorInterface {

    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'NAND';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return !parent::assert();
    }
}
