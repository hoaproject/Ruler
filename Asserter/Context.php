<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Ruler
 */
-> import('Ruler.Ruler');

}

namespace Hoa\Ruler\Asserter {

/**
 * Context
 *
 * @uses \Pimple
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class Context implements \ArrayAccess {

    /**
     * @var \Hoa\Ruler\Ruler
     */
    private $ruler;

    /**
     * @var array
     */
    protected $values;

    /**
     * @param array $values values
     */
    public function __construct (array $values = array()) {

        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet ( $id, $value ) {

        $this->values[$id] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet ( $id ) {

        if (!array_key_exists($id, $this->values)) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        if ($this->values[$id] instanceof \Hoa\Ruler\Asserter\DynamicClosure)
            return $this->values[$id]($this);

        if ($this->values[$id] instanceof \Closure)
            $this->values[$id] = $this->values[$id]($this);

        return $this->values[$id];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists ( $id ) {

        return array_key_exists($id, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset ( $id ) {

        unset($this->values[$id]);
    }

    /**
     * @param \Hoa\Ruler\Ruler $ruler ruler
     */
    public function setRuler ( \Hoa\Ruler\Ruler $ruler ) {

        $this->ruler = $ruler;
    }

    /**
     * @return Ruler
     */
    public function getRuler ( ) {

        return $this->ruler;
    }
}

}
