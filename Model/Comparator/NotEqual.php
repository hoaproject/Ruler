<?php

namespace Hoa\Ruler\Model\Comparator {

/**
 * NotEqual
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class NotEqual extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return '!=';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return $this->left->getValue() != $this->right->getValue();
    }
}

}
