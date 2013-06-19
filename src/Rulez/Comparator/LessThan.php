<?php

namespace Rulez\Comparator;

/**
 * LessThan
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class LessThan extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return '<';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return $this->left < $this->right;
    }
}
