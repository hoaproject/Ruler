<?php

namespace Rulez\Comparator;

/**
 * IsNot
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class IsNot extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return 'IS NOT';
    }

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        return $this->left != $this->right;
    }
}
