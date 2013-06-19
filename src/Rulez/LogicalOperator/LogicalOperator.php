<?php

namespace Rulez\LogicalOperator;

use Rulez\Comparator\ComparatorInterface;
use Rulez\Asserter\Context;

/**
 * LogicalOperator
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class LogicalOperator {

    /**
     * @var array
     */
    protected $conditions = array();

    /**
     * @return string
     */
    public function __toString ( ) {

        $parts = array();

        foreach ($this->getConditions() as $condition) {
            if ($condition instanceof LogicalOperator) {
                $condition = '('.(string) $condition.')';
            }

            $parts[] = $condition;
        }

        return implode(sprintf(' %s ', $this->getToken()), $parts);
    }

    /**
     * Constructor, pass as many condition|operator as you want
     */
    public function __construct ( ) {

        $args = func_get_args();

        if (count($args) < 2) {
            throw new \InvalidArgumentException('Logical operators accepts minimum 2 arguments');
        }

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
    public function transform ( Context $context ) {

        foreach ($this->getConditions() as $condition) {
            $condition->transform($context);
        }
    }

    /**
     * @param Condition|Operator $data data
     */
    public function append ( $data ) {

        if (!$data instanceof ComparatorInterface && !$data instanceof LogicalOperatorInterface) {
            throw new \InvalidArgumentException('LogicalOperator accepts only comparator and logical operators');
        }

        $this->conditions[] = $data;
    }

    /**
     * @param Condition|Operator $data data
     */
    public function prepend ( $data ) {

        if (!$data instanceof ComparatorInterface && !$data instanceof LogicalOperatorInterface) {
            throw new \InvalidArgumentException('LogicalOperator accepts only comparator and logical operators');
        }

        array_unshift($this->conditions, $data);
    }

    /**
     * @return array
     */
    public function getConditions ( ) {

        return $this->conditions;
    }
}
