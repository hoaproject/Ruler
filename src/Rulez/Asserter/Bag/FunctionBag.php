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
class FunctionBag implements BagInterface {

    /**
     * @var string
     */
    protected $functionName;

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @param string $functionName functionName
     * @param array  $arguments    arguments
     */
    public function __construct ( $functionName, array $arguments = array() ) {

        $this->functionName = $functionName;
        $this->arguments    = $arguments;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString ( ) {

        return sprintf('%s(%s)', $this->functionName, implode(', ', $this->arguments));
    }

    /**
     * {@inheritdoc}
     */
    public function transform ( Context $context ) {

        $ruler = $context->getRuler();

        if (!$ruler->hasFunction($this->functionName))
            throw new UnknownFunctionReferenceException(sprintf('Function reference "%s" does not exists.', $this->functionName));

        $arguments = array();
        foreach ($this->arguments as $k => $argument) {
            if ($argument instanceof BagInterface) {
                $arguments[$k] = $argument->transform($context);
            }
        }

        $function = $ruler->getFunction($this->functionName);

        $this->value = $function($this->arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        return $this->value;
    }

}
