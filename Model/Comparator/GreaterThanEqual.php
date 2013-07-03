<?php

namespace Hoa\Ruler\Model\Comparator {

/**
 * GreaterThanEqual
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class GreaterThanEqual extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return '>=';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return $this->left->getValue() >= $this->right->getValue();
    }
}

}
