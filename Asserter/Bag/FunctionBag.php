<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context')

/**
 * \Hoa\Ruler\Exception\UnknownFunctionReferenceException
 */
-> import('Ruler.Exception.UnknownFunctionReferenceException');

}

namespace Hoa\Ruler\Asserter\Bag {

/**
 * FunctionBag
 *
 * @uses BagInterface
 * @author Stephane PY <stephane.py@hoa-project.net>
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
    public function transform ( \Hoa\Ruler\Asserter\Context $context ) {

        $ruler = $context->getRuler();

        if (!$ruler->hasFunction($this->functionName))
            throw new \Hoa\Ruler\Exception\UnknownFunctionReferenceException(sprintf('Function reference "%s" does not exists.', $this->functionName));

        $arguments = array();
        foreach ($this->arguments as $k => $argument) {
            if ($argument instanceof BagInterface) {
                $argument->transform($context);

                $arguments[$k] = $argument->getValue();
            }
        }

        $function = $ruler->getFunction($this->functionName);

        $this->value = $function($arguments);
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
