<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Bag\*
 */
-> import('Ruler.Asserter.Bag.*')

/**
 * \Hoa\Ruler\Exception\UnknownComparatorException
 */
-> import('Ruler.Exception.UnknownComparatorException')

/**
 * \Hoa\Ruler\Model\Operator\*
 */
-> import('Ruler.Model.Operator.*')

/**
 * \Hoa\Ruler\Ruler
 */
-> import('Ruler.Ruler')

/**
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

}

namespace Hoa\Ruler\Visitor {

/**
 * Interpreter
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class Interpreter implements \Hoa\Visitor\Visit {

    /**
     * @var Ruler
     */
    protected $ruler;

    /**
     * @param Rumer $ruler ruler
     */
    public function __construct ( \Hoa\Ruler\Ruler $ruler ) {

        $this->ruler = $ruler;
    }

    public function visit ( \Hoa\Visitor\Element $element, &$handle = null, $eldnah = null ) {

        $type     = $element->getId();
        $children = $element->getChildren();

        foreach ($children as &$child) {
            $child = $child->accept($this, $handle, $eldnah);
        }

        $operator = function($class, $children) {
            $left = $children[0];
            $right = $children[1];

            if ($right instanceof Hoa\Ruler\Model\Operator\LogicalInterface && $right instanceof $class) {
                $right->prepend($left);
                return $right;
            }

            return new $class($left, $right);
        };

        switch ($type) {
            case '#function':
                $functionName = (string) array_shift($children);

                return new \Hoa\Ruler\Asserter\Bag\FunctionBag($functionName, $children);
            break;
            case '#array':
                return new \Hoa\Ruler\Asserter\Bag\ArrayBag($children);
                break;
            case '#not':
                return new \Hoa\Ruler\Model\Operator\LogicalNot($children);
            break;
            case '#and':
                return $operator('\Hoa\Ruler\Model\Operator\LogicalAnd', $children);
            break;
            case '#or':
                return $operator('\Hoa\Ruler\Model\Operator\LogicalOr', $children);
            break;
            case '#xor':
                return $operator('\Hoa\Ruler\Model\Operator\LogicalXOr', $children);
            break;
            case '#condition':
                $comparator = (string) $children[1];

                if (!$this->ruler->hasComparator($comparator))
                    throw new \Hoa\Ruler\Exception\UnknownComparatorException(sprintf('Comparator "%s" is not supported.', $comparator));

                $class = $this->ruler->getComparator($comparator);

                return new $class($children[0], $children[2]);
            break;
            case 'token':
                $value = $element->getValueValue();
                $token = $element->getValueToken();
                $quote = null;

                switch ($token) {
                    case 'null':
                        $v = null;
                        break;
                    case 'true':
                        $v = true;
                        break;
                    case 'false':
                        $v = false;
                        break;
                    case 'number':
                        $v = (int) $value;
                        break;
                    case 'float':
                        $v = (float) $value;
                    case 'string':
                        // If string begins by " or ', we first and end char.
                        $quote = $value[0];
                        $v     = substr($value, 1, -1);
                        break;
                    case 'key':
                        return new \Hoa\Ruler\Asserter\Bag\ContextBag($value);
                        break;
                    default:
                        $v = $value;
                        break;
                }

                return new \Hoa\Ruler\Asserter\Bag\ScalarBag($v, $quote);

                break;
            default:
                throw new \LogicException(sprintf('"%s" type not supported in interpretation system.', $type));
                break;
        }
    }
}

}
