<?php

namespace Rulez\Asserter\Bag;

use Rulez\Asserter\Context;
use Rulez\Exception\UnknownFunctionReferenceException;

/**
 * FunctionBag
 *
 * @uses BagInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class FunctionBag implements BagInterface
{
    /**
     * @var string
     */
    protected $functionName;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @param string $functionName functionName
     * @param array  $arguments    arguments
     */
    public function __construct($functionName, array $arguments = array())
    {
        $this->functionName = $functionName;
        $this->arguments    = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('%s(%s)', $this->functionName, implode(', ', $this->arguments));
    }

    /**
     * {@inheritdoc}
     */
    public function transform(Context $context)
    {
        $ruler = $context->getRuler();

        if (!$ruler->hasFunction($this->functionName)) {
            throw new UnknownFunctionReferenceException(sprintf('Function reference "%s" does not exists.', $this->functionName));
        }

        foreach ($this->arguments as $k => $argument) {
            if ($argument instanceof BagInterface) {
                $this->arguments[$k] = $argument->transform($context);
            }
        }

        $function = $ruler->getFunction($this->functionName);

        return $function($this->arguments);
    }
}
