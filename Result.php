<?php

namespace Hoa\Ruler;

use Hoa\Ruler\Exception\Exception;

/**
 * @property string name
 * @property Rule   rule
 * @property mixed  $result
 */
class Result
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Rule
     */
    private $rule;

    /**
     * @var mixed
     */
    private $outcome;

    /**
     * @param string $name
     * @param Rule   $rule
     * @param mixed  $result
     */
    public function __construct($name, Rule $rule, $result)
    {
        $this->name   = $name;
        $this->rule   = $rule;
        $this->result = $result;
    }

    /**
     * @param string $name
     *
     * @throws \Error
     *
     * @return mixed
     */
    public function __get($name)
    {
        switch (strtolower($name)) {
            case 'name':
                return $name;

            case 'result':
                if (is_object($this->result)) {
                    if ($this->result instanceof \Closure) {
                        return $this->result;
                    }

                    return clone $this->result;
                }

                return $this->result;

            case 'rule':
                return clone $this->rule;

            default:
                throw new Exception('Unable to get property ' . $name);
        }
    }
}
