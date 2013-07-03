<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Model\Operator {

/**
 * UnaryOperator
 *
 * @uses LogicalInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
abstract class UnaryOperator implements LogicalInterface {

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

        if ($condition instanceof Operator) {
            $condition = sprintf('(%s)', (string) $condition);
        }

        return $this->getToken().' '.(string) $condition;
    }

    /**
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

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

}
