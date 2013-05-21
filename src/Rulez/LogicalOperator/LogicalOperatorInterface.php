<?php

namespace Rulez\LogicalOperator;

use Rulez\Asserter\Context;

/**
 * LogicalOperatorInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface LogicalOperatorInterface
{
    /**
     * @param Context $context context
     *
     * @return boolean
     */
    public function assert();

    /**
     * @param Context $context context
     */
    public function transformContextReferences(Context $context);

    /**
     * @return string
     */
    public function getToken();

    /**
     * @param Condition|Operator $data data
     */
    public function append($data);

    /**
     * @param Condition|Operator $data data
     */
    public function prepend($data);

    /**
     * @return array
     */
    public function getConditions();
}
