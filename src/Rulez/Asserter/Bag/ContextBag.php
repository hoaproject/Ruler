<?php

namespace Rulez\Asserter\Bag;

use Rulez\Asserter\Context;
use Rulez\Exception\UnknownContextReferenceException;

/**
 * ContextBag
 *
 * @uses BagInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ContextBag implements BagInterface
{
    /**
     * @var string
     */
    protected $str;

    /**
     * @param string $str str
     */
    public function __construct($str)
    {
        $this->str = $str;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->str;
    }

    /**
     * {@inheritdoc}
     */
    public function transform(Context $context)
    {
        if (!isset($context[$this->str])) {
            throw new UnknownContextReferenceException(sprintf('Context reference "%s" does not exists.', $this->str));
        }

        return $context[$this->str];
    }

}
