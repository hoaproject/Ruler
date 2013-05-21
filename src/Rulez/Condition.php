<?php

namespace Rulez;

/**
 * Condition
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Condition
{
    /**
     * @var string
     */
    public $left;

    /**
     * @var string
     */
    public $operator;

    /**
     * @var mixed
     */
    public $right;

    /**
     * @param string $left     left
     * @param string $operator operator
     * @param mixed  $right    right
     */
    public function __construct($left, $operator, $right)
    {
        $this->left = $left;
        $this->operator = $operator;
        $this->right = $right;
    }
}
