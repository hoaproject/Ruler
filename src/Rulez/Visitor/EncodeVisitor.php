<?php

namespace Rulez\Visitor;

use Rulez\Asserter\ContextReference;
use Rulez\Asserter\FunctionReference;
use Rulez\Comparator\ComparatorInterface;
use Rulez\LogicalOperator\LogicalOperatorInterface;

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
        if ($element instanceof ComparatorInterface) {
            return $this->visit($element->getLeft()).' '.$element->getToken().' '.$this->visit($element->getRight(), false);
        } elseif ($element instanceof LogicalOperatorInterface) {
            $parts = array();

            foreach ($element->getConditions() as $condition) {
                $parts[] = $this->visit($condition, false);
            }

            $str = implode(sprintf(' %s ', $element->getToken()), $parts);

            if (!$isRoot) {
                $str = sprintf('(%s)', $str);
            }

            return $str;
        } elseif ($element instanceof FunctionReference) {
            $args = array();
            foreach ($element->getArguments() as $argument) {
                $args[] = $this->visit($argument, false);
            }

            return sprintf('%s(%s)', $element->getFunctionName(), implode(', ', $args));

        } elseif ($element instanceof ContextReference) {
            return (string) $element;
        } elseif (null === $element) {
            return 'NULL';
        } elseif (false === $element) {
            return 'FALSE';
        } elseif (true === $element) {
            return 'TRUE';
        } elseif (is_numeric($element)) {
            return $element;
        } else {
            return sprintf('"%s"', $element);
        }

        return $str;
    }

}
