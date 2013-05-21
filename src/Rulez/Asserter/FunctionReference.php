<?php

namespace Rulez\Asserter;

use Rulez\Exception\UnknownFunctionReferenceException;

/**
 * FunctionReference
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class FunctionReference
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
     * @param Context $context context
     *
     * @return mixed
     */
    public function transform(Context $context)
    {
        $ruler = $context->getRuler();

        if (!$ruler->hasFunction($this->functionName)) {
            throw new UnknownFunctionReferenceException(sprintf('Function reference "%s" does not exists.', $this->functionName));
        }

        foreach ($this->arguments as $k => $argument) {
            if ($argument instanceof ContextReference || $argument instanceof FunctionReference) {
                $this->arguments[$k] = $argument->transform($context);
            }
        }

        $function = $ruler->getFunction($this->functionName);

        return $function($this->arguments);
    }

    /**
     * @return string
     */
    public function getFunctionName()
    {
        return $this->functionName;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }
}
