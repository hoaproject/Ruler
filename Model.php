<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Bag\*
 */
-> import('Ruler.Asserter.Bag.*')

/**
 * \Hoa\Ruler\Model\Comparator\*
 */
-> import('Ruler.Model.Comparator.*')

/**
 * \Hoa\Ruler\Model\Operator\*
 */
-> import('Ruler.Model.Operator.*');

}


namespace Hoa\Ruler {

/**
 * Model
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Model
{
    public function __call ( $name, array $arguments ) {

        switch ($name) {
            case 'and':
                return dnew('\Hoa\Ruler\Model\Operator\LogicalAnd', $arguments);
                break;
            case 'or':
                return dnew('\Hoa\Ruler\Model\Operator\LogicalOr', $arguments);
                break;
            case 'xor':
                return dnew('\Hoa\Ruler\Model\Operator\LogicalXOr', $arguments);
                break;
            case 'array':
                return dnew('\Hoa\Ruler\Asserter\Bag\ArrayBag', $arguments);
                break;
            case 'function':
                return dnew('\Hoa\Ruler\Asserter\Bag\FunctionBag', $arguments);
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Method "%s" not found in %s', $name, __CLASS__));
                break;
        }
    }

    public function scalar ( $value, $quote = '"' ) {

        return new Asserter\Bag\ScalarBag($value, $quote);
    }

    public function not ( ) {

        return dnew('\Hoa\Ruler\Model\Operator\LogicalNot', func_get_args());
    }

    public function context ( $name ) {

        return new Asserter\Bag\ContextBag($name);
    }

    public function equals ( $left, $right ) {

        return new Model\Comparator\Equal($left, $right);
    }

    public function greaterThan ( $left, $right ) {

        return $this->gt($left, $right);
    }

    public function gt ( $left, $right ) {

        return new Model\Comparator\GreaterThan($left, $right);
    }

    public function greaterThanEqual ( $left, $right ) {
        return $this->gte($left, $right);
    }

    public function gte ( $left, $right ) {

        return new Model\Comparator\GreaterThanEqual($left, $right);
    }

    public function in ( $left, $right ) {

        return new Model\Comparator\In($left, $right);
    }

    public function is ( $left, $right ) {

        return new Model\Comparator\Is($left, $right);
    }

    public function isNot ( $left, $right ) {

        return new Model\Comparator\IsNot($left, $right);
    }

    public function lessThan ( $left, $right ) {

        return $this->lt($left, $right);
    }

    public function lt ( $left, $right ) {

        return new Model\Comparator\LessThan($left, $right);
    }

    public function lessThanEqual ( $left, $right ) {

        return $this->lte($left, $right);
    }

    public function lte ( $left, $right ) {

        return new Model\Comparator\LessThanEqual($left, $right);
    }

    public function notEquals ( $left, $right ) {

        return new Model\Comparator\NotEqual($left, $right);
    }
}

}
