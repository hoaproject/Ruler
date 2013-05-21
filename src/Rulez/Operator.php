<?php

namespace Rulez;

/**
 * Operator
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Operator
{
    CONST TYPE_AND  = 'AND';
    CONST TYPE_NAND = 'NAND';
    CONST TYPE_OR   = 'OR';
    CONST TYPE_NOR  = 'NOR';
    CONST TYPE_XNOR = 'XNOR';
    CONST TYPE_XOR  = 'XOR';

    /**
     * @var array
     */
    protected $conditions = array();

    /**
     * @var string
     */
    protected $type;

    /**
     * Constructor, pass as many condition|operator as you want
     */
    public function __construct()
    {
        $args = func_get_args();
        if (!is_scalar($type = array_shift($args))) {
            throw new \LogicException('First argument has to be a type');
        }

        $this->type = $type;

        foreach ($args as $arg) {
            $this->append($arg);
        }
    }

    /**
     * @param Condition|Operator $data data
     */
    public function append($data)
    {
        if (!$data instanceof Condition && !$data instanceof Operator) {
            throw new \LogicException('Operator accepts only condition and operators');
        }

        $this->conditions[] = $data;
    }

    /**
     * @param Condition|Operator $data data
     */
    public function prepend($data)
    {
        if (!$data instanceof Condition && !$data instanceof Operator) {
            throw new \LogicException('Operator accepts only condition and operators');
        }

        array_unshift($this->conditions, $data);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getConditions()
    {
        return $this->conditions;
    }
}
