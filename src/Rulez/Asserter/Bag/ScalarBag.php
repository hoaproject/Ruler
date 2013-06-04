<?php

namespace Rulez\Asserter\Bag;

use Rulez\Asserter\Context;

/**
 * ScalarBag
 *
 * @uses BagInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ScalarBag implements BagInterface
{
    /**
     * @var string
     */
    protected $str;

    protected $quote;

    /**
     * @param string $str str
     */
    public function __construct($str, $quote)
    {
        $this->str = $str;
        $this->quote = $quote;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
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
    public function transform(Context $context)
    {
        return $this->str;
    }
}
