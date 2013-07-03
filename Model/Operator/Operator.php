<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context')

/**
 * \Hoa\Ruler\Model\Comparator\ComparatorInterface
 */
-> import('Ruler.Model.Comparator.ComparatorInterface');

}

namespace Hoa\Ruler\Model\Operator {

/**
 * Operator
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
abstract class Operator {

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
            if ($condition instanceof Operator) {
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
            throw new \InvalidArgumentException('Operators accepts minimum 2 arguments');
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
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        foreach ($this->getConditions() as $condition) {
            $condition->transform($context);
        }
    }

    /**
     * @param ComparatorInterface|Operator $data data
     */
    public function append ( $data ) {

        if (!$data instanceof \Hoa\Ruler\Model\Comparator\ComparatorInterface && !$data instanceof LogicalInterface) {
            throw new \InvalidArgumentException('Operator accepts only comparator and logical operators');
        }

        $this->conditions[] = $data;
    }

    /**
     * @param ComparatorInterface|Operator $data data
     */
    public function prepend ( $data ) {

        if (!$data instanceof \Hoa\Ruler\Model\Comparator\ComparatorInterface && !$data instanceof LogicalInterface) {
            throw new \InvalidArgumentException('Operator accepts only comparator and logical operators');
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

}
