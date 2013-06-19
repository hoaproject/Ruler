<?php

namespace Rulez\LogicalOperator;

use Rulez\Asserter\Context;

/**
 * UnaryLogicalOperator
 *
 * @uses LogicalOperator
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class UnaryLogicalOperator implements LogicalOperatorInterface {

    /**
     * @var mixed
     */
    protected $condition;

    /**
     * Constructor, pass as many condition|operator as you want
     */
    public function __construct ( ) {

        $args = func_get_args();

        if (count($args) != 1) {
            throw new \InvalidArgumentException(sprintf('"%s" logical operator is unary, it accepts only one argument.', $this->getToken()));
        }

        foreach ($args as $arg) {
            if (!is_array($arg)) {
                $arg = array($arg);
            }

            $this->condition = current($arg);
        }
    }

    /**
     * @return string
     */
    public function __toString ( ) {

        $condition = $this->condition;

        if ($condition instanceof LogicalOperator) {
            $condition = sprintf('(%s)', (string) $condition);
        }

        return $this->getToken().' '.(string) $condition;
    }

    /**
     * @param Context $context context
     */
    public function transform ( Context $context ) {

        $this->condition->transform($context);
    }


    /**
     * Left or|and right on comparators could have unary operator, getValue method has to be implemented
     *
     * @return mixed:
     */
    public function getValue()
    {
        return $this->assert();
    }
}
