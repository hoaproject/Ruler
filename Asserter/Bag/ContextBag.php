<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context')

/**
 * \Hoa\Ruler\Exception\UnknownContextReferenceException
 */
-> import('Ruler.Exception.UnknownContextReferenceException');

}

namespace Hoa\Ruler\Asserter\Bag {

/**
 * ContextBag
 *
 * @uses BagInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class ContextBag implements BagInterface {

    /**
     * @var string
     */
    protected $str;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $str str
     */
    public function __construct ( $str ) {

        $this->str = $str;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString ( ) {

        return (string) $this->str;
    }

    /**
     * {@inheritdoc}
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        if (!isset($context[$this->str]))
            throw new \Hoa\Ruler\Exception\UnknownContextReferenceException(sprintf('Context reference "%s" does not exists.', $this->str));

        $this->value = $context[$this->str];
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
