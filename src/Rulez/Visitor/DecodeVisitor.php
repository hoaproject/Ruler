<?php

namespace Rulez\Visitor;

from('Hoa')

/**
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

class DecodeVisitor implements \Hoa\Visitor\Visit
{
    public function visit ( \Hoa\Visitor\Element $element, &$handle = null, $eldnah = null )
    {
        $type     = $element->getId();
        $children = $element->getChildren();

        foreach ($children as &$child) {
            $child = $child->accept($this, $handle, $eldnah);
        }

        $operator = function($type, $children) {
            $left = $children[0];
            $right = $children[1];

            if ($right instanceof \Rulez\Operator && $right->getType() == $type) {
                $right->prepend($left);
                return $right;
            }

            return new \Rulez\Operator($type, $left, $right);
        };

        switch ($type) {
            case '#and':
                return $operator(\Rulez\Operator::TYPE_AND, $children);
            break;
            case '#nand':
                return $operator(\Rulez\Operator::TYPE_NAND, $children);
            break;
            case '#or':
                return $operator(\Rulez\Operator::TYPE_OR, $children);
            break;
            case '#nor':
                return $operator(\Rulez\Operator::TYPE_NOR, $children);
            break;
            case '#xnor':
                return $operator(\Rulez\Operator::TYPE_XNOR, $children);
            break;
            case '#xor':
                return $operator(\Rulez\Operator::TYPE_XOR, $children);
            break;
            case '#condition':
                return new \Rulez\Condition($children[0], $children[1], $children[2]);
            break;
            case 'token':
                $value = $element->getValueValue();
                $token = $element->getValueToken();

                switch ($token) {
                    case 'null':
                        return null;
                        break;
                    case 'true':
                        return true;
                        break;
                    case 'false':
                        return false;
                        break;
                    case 'number':
                        return (int) $value;
                        break;
                    case 'float':
                        return (float) $value;
                    case 'string':
                        // If string begins by " or ', we first and end char.
                        return substr($value, 1, -1);
                        break;
                    default:
                        return $value;
                        break;
                }
                break;
        }
    }

}
