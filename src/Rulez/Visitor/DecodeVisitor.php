<?php

namespace Rulez\Visitor;

use Rulez\Ruler;
use Rulez\Exception;
use Rulez\LogicalOperator;
use Rulez\Asserter\Bag;

from('Hoa')

/**
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

class DecodeVisitor implements \Hoa\Visitor\Visit {

    /**
     * @var Ruler
     */
    protected $ruler;

    /**
     * @param Rumer $ruler ruler
     */
    public function __construct ( Ruler $ruler ) {

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

            if ($right instanceof LogicalOperator\LogicalOperatorInterface && $right instanceof $class) {
                $right->prepend($left);
                return $right;
            }

            return new $class($left, $right);
        };

        switch ($type) {
            case '#function':
                $functionName = (string) array_shift($children);

                return new Bag\FunctionBag($functionName, $children);
            break;
            case '#array':
                return new Bag\ArrayBag($children);
                break;
            case '#not':
                return new \Rulez\LogicalOperator\LogicalNot($children);
            break;
            case '#and':
                return $operator('\Rulez\LogicalOperator\LogicalAnd', $children);
            break;
            case '#nand':
                return $operator('\Rulez\LogicalOperator\LogicalNAnd', $children);
            break;
            case '#or':
                return $operator('\Rulez\LogicalOperator\LogicalOr', $children);
            break;
            case '#nor':
                return $operator('\Rulez\LogicalOperator\LogicalNOr', $children);
            break;
            case '#xnor':
                return $operator('\Rulez\LogicalOperator\LogicalXNOr', $children);
            break;
            case '#xor':
                return $operator('\Rulez\LogicalOperator\LogicalXOr', $children);
            break;
            case '#condition':
                $comparator = (string) $children[1];

                if (!$this->ruler->hasComparator($comparator))
                    throw new Exception\UnknownComparatorException(sprintf('Comparator "%s" is not supported.', $comparator));

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
                        return new Bag\ContextBag($value);
                        break;
                    default:
                        $v = $value;
                        break;
                }

                return new Bag\ScalarBag($v, $quote);

                break;
            default:
                throw new \LogicException(sprintf('"%s" type not supported in decode system.', $type));
                break;
        }
    }

}
