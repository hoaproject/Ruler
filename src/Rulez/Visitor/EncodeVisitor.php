<?php

namespace Rulez\Visitor;

use Rulez\Condition;
use Rulez\Operator;

/**
 * EncodeVisitor
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class EncodeVisitor
{
    /**
     * @param Condition|Operator $element element
     *
     * @return string
     */
    public function visit($element, $isRoot = true)
    {
        $str = '';
        if ($element instanceof Condition) {
            $left = $element->left;
            if (preg_match('/(\s|\'|\")/', $left)) {
                $left = sprintf('"%s"', $left);
            }

            $str .= sprintf('%s %s ', $left, $element->operator);

            $right = $element->right;

            if (null === $right) {
                $str .= 'NULL';
            } elseif (false === $right) {
                $str .= 'FALSE';
            } elseif (true === $right) {
                $str .= 'TRUE';
            } elseif (is_numeric($right)) {
                $str .= $right;
            } else {
                $str .= sprintf('"%s"', $right);
            }

        } elseif ($element instanceof Operator) {
            $parts = array();

            foreach ($element->getConditions() as $condition) {
                $parts[] = $this->visit($condition, false);
            }

            $str = implode(sprintf(' %s ', $element->getType()), $parts);

            if (!$isRoot) {
                $str = sprintf('(%s)', $str);
            }
        } else {
            throw new \LogicException('EncodeVisitor accepts Condition or Operator');
        }

        return $str;
    }

}
