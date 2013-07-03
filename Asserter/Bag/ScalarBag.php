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
 * ScalarBag
 *
 * @uses BagInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class ScalarBag implements BagInterface {

    /**
     * @var string
     */
    protected $str;

    /**
     * @var string
     */
    protected $quote;

    /**
     * @param string $str   str
     * @param string $quote quote
     */
    public function __construct ( $str, $quote = '"' ) {

        $this->str   = $str;
        $this->quote = $quote;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString ( ) {

        if (null === $this->str) {
            return 'NULL';
        } elseif (false === $this->str) {
            return 'FALSE';
        } elseif (true === $this->str) {
            return 'TRUE';
        } elseif (is_numeric($this->str)) {
            return (string) $this->str;
        } else {
            if ($this->quote) {
                return $this->quote.$this->str.$this->quote;
            } else {
                return $this->str;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        return $this->str;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->str;
    }

}

}
