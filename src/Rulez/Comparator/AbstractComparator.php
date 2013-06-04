<?php

namespace Rulez\Comparator;

use Rulez\Asserter\Context;
use Rulez\Asserter\Bag\BagInterface;
use Rulez\Exception\UnknownContextReferenceException;

/**
 * AbstractComparator
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
abstract class AbstractComparator
{
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
    public function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->left.' '.$this->getToken().' '.(string) $this->right;
    }

    /**
     * @return string
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param Context $context context
     */
    public function transform(Context $context)
    {
        if ($this->left instanceof BagInterface) {
            $this->left = $this->left->transform($context);
        }

        if ($this->right instanceof BagInterface) {
            $this->right = $this->right->transform($context);
        }
    }
}
