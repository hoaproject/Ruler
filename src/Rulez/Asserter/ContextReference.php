<?php

namespace Rulez\Asserter;

use Rulez\Exception\UnknownContextReferenceException;

/**
 * ContextReference
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ContextReference
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
     * @return string
     */
    public function __toString()
    {
        return (string) $this->str;
    }

    /**
     * @param Context $context context
     *
     * @return mixed
     */
    public function transform(Context $context)
    {
        if (!isset($context[$this->str])) {
            throw new UnknownContextReferenceException(sprintf('Context reference "%s" does not exists.', $this->str));
        }

        return $context[$this->str];
    }
}
