<?php

namespace Rulez\Comparator;

/**
 * GreaterThanEqual
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
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
