<?php

namespace Rulez\Comparator;

use Rulez\Asserter\Context;
use Rulez\Asserter\ContextReference;
use Rulez\Asserter\FunctionReference;
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
    public function transformContextReferences(Context $context)
    {
        if ($this->left instanceof ContextReference || $this->left instanceof FunctionReference) {
            $this->left = $this->left->transform($context);
        }

        if ($this->right instanceof ContextReference || $this->right instanceof FunctionReference) {
            $this->right = $this->right->transform($context);
        }
    }
}
