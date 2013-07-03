<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Bag\BagInterface
 */
-> import('Ruler.Asserter.Bag.BagInterface')

/**
 * \Hoa\Ruler\Asserter\Bag\ScalarBag
 */
-> import('Ruler.Asserter.Bag.ScalarBag')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Model\Comparator {

/**
 * AbstractComparator
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
abstract class AbstractComparator {

    /**
     * @var string
     */
    protected $left;

    /**
     * @var mixed
     */
    protected $right;

    /**
     * @param string $left  left
     * @param mixed  $right right
     */
    public function __construct ( $left, $right ) {

        if (!$left instanceof \Hoa\Ruler\Asserter\Bag\BagInterface) {
            $left = new \Hoa\Ruler\Asserter\Bag\ScalarBag($left);
        }

        if (!$right instanceof \Hoa\Ruler\Asserter\Bag\BagInterface) {
            $right = new \Hoa\Ruler\Asserter\Bag\ScalarBag($right);
        }

        $this->left  = $left;
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function __toString ( ) {

        return (string) $this->left.' '.$this->getToken().' '.(string) $this->right;
    }

    /**
     * @return string
     */
    public function getLeft ( ) {

        return $this->left;
    }

    /**
     * @return mixed
     */
    public function getRight ( ) {

        return $this->right;
    }

    /**
     * @param \Hoa\Ruler\Asserter\Context $context context
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        if ($this->left instanceof \Hoa\Ruler\Asserter\Bag\BagInterface)
            $this->left->transform($context);

        if ($this->right instanceof \Hoa\Ruler\Asserter\Bag\BagInterface)
            $this->right->transform($context);
    }
}

}
