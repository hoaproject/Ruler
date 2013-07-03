<?php

namespace Hoa\Ruler\Model\Comparator {

/**
 * In
 *
 * @uses AbstractComparator
 * @uses ComparatorInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class In extends AbstractComparator implements ComparatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getToken ( ) {

        return 'IN';
    }

    /**
     * {@inheritdoc}
     */
    public function assert ( ) {

        $right = $this->right->getValue();

        if (!is_array($right))
            return false;

        return in_array($this->left->getValue(), $right);
    }
}

}
