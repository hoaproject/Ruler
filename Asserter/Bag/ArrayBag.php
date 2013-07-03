<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context');

}

namespace Hoa\Ruler\Asserter\Bag {

/**
 * ArrayBag
 *
 * @uses BagInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class ArrayBag implements BagInterface {

    /**
     * @var array
     */
    protected $data;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param array $data data
     */
    public function __construct ( array $data ) {

        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString ( ) {

        return sprintf('(%s)', implode(', ', $this->data));
    }

    /**
     * {@inheritdoc}
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        foreach ($this->data as $k => $data) {
            if ($data instanceof BagInterface) {
                $this->value[$k] = $data->transform($context);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }
}

}
