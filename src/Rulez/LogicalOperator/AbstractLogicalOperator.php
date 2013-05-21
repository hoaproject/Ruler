<?php

namespace Rulez\LogicalOperator;

use Rulez\Comparator\ComparatorInterface;
use Rulez\Asserter\Context;

/**
 * AbstractLogicalOperator
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class AbstractLogicalOperator
{
    /**
     * @var array
     */
    protected $conditions = array();

    /**
     * Constructor, pass as many condition|operator as you want
     */
    public function __construct()
    {
        $args = func_get_args();

        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $arg = array($arg);
            }

            foreach ($arg as $argument) {
                $this->append($argument);
            }
        }
    }

    /**
     * @param Context $context context
     */
    public function transformContextReferences(Context $context)
    {
        foreach ($this->getConditions() as $condition) {
            $condition->transformContextReferences($context);
        }
    }

    /**
     * @param Condition|Operator $data data
     */
    public function append($data)
    {
        if (!$data instanceof ComparatorInterface && !$data instanceof LogicalOperatorInterface) {
            throw new \LogicException('LogicalOperator accepts only comparator and logical operators');
        }

        $this->conditions[] = $data;
    }

    /**
     * @param Condition|Operator $data data
     */
    public function prepend($data)
    {
        if (!$data instanceof ComparatorInterface && !$data instanceof LogicalOperatorInterface) {
            throw new \LogicException('LogicalOperator accepts only comparator and logical operators');
        }

        array_unshift($this->conditions, $data);
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }

}
