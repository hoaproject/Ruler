<?php

namespace Hoa\Ruler\Model\Comparator {

/**
 * Is
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Is extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'IS';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        return $this->left->getValue() == $this->right->getValue();
    }
}

}
