<?php

namespace Rulez\Comparator;

/**
 * In
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class In extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken()
    {
        return 'IN';
    }

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        if (!is_array($this->right)) {
            return false;
        }

        return in_array($this->left, $this->right);
    }
}
