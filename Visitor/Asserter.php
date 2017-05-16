<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2017, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Ruler\Visitor;

use ArrayAccess;
use Hoa\Consistency;
use Hoa\Ruler;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Visitor\Asserter.
 *
 * Asserter: evaluate a model representing a rule.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Asserter implements Visitor\Visit
{
    /**
     * Context.
     *
     * @var \Hoa\Ruler\Context
     */
    protected $_context   = null;

    /**
     * List of operators.
     *
     * @var array
     */
    protected $_operators = [];



    /**
     * Constructor.
     *
     * @param   \Hoa\Ruler\Context  $context    Context.
     */
    public function __construct(Ruler\Context $context = null)
    {
        if (null !== $context) {
            $this->setContext($context);
        }

        $this->setOperator('and', function ($a = false, $b = false) { return $a && $b; });
        $this->setOperator('or',  function ($a = false, $b = false) { return $a || $b; });
        $this->setOperator('xor', function ($a, $b) { return (bool) ($a ^ $b); });
        $this->setOperator('not', function ($a) { return !$a; });
        $this->setOperator('=',   function ($a, $b) { return $a == $b; });
        $this->setOperator('is',  $this->getOperator('='));
        $this->setOperator('!=',  function ($a, $b) { return $a != $b; });
        $this->setOperator('>',   function ($a, $b) { return $a >  $b; });
        $this->setOperator('>=',  function ($a, $b) { return $a >= $b; });
        $this->setOperator('<',   function ($a, $b) { return $a <  $b; });
        $this->setOperator('<=',  function ($a, $b) { return $a <= $b; });
        $this->setOperator('in',  function ($a, array $b) { return in_array($a, $b); });
        $this->setOperator('sum', function () { return array_sum(func_get_args()); });
        $this->setOperator('matches', function ($subject, $pattern) {
            $escapedPattern = preg_replace('/(?<!\\\)`/', '\`', $pattern);

            return 0 !== preg_match('`' . $escapedPattern . '`', $subject);
        });

        return;
    }

    /**
     * Visit an element.
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     * @throws  \Hoa\Ruler\Exception\Asserter
     */
    public function visit(Visitor\Element $element, &$handle = null, $eldnah = null)
    {
        if ($element instanceof Ruler\Model) {
            return $this->visitModel($element, $handle, $eldnah);
        }

        if ($element instanceof Ruler\Model\Operator) {
            return $this->visitOperator($element, $handle, $eldnah);
        }

        if ($element instanceof Ruler\Model\Bag\Scalar) {
            return $this->visitScalar($element, $handle, $eldnah);
        }

        if ($element instanceof Ruler\Model\Bag\RulerArray) {
            return $this->visitArray($element, $handle, $eldnah);
        }

        if ($element instanceof Ruler\Model\Bag\Context) {
            return $this->visitContext($element, $handle, $eldnah);
        }
    }

    /**
     * Visit a model
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    public function visitModel(Ruler\Model $element, &$handle = null, $eldnah = null)
    {
        return (bool) $element->getExpression()->accept($this, $handle, $eldnah);
    }

    /**
     * Visit an operator
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    protected function visitOperator(Ruler\Model\Operator $element, &$handle = null, $eldnah = null)
    {
        $name      = $element->getName();
        $arguments = [];

        foreach ($element->getArguments() as $argument) {
            $value       = $argument->accept($this, $handle, $eldnah);
            $arguments[] = $value;

            if ($element::LAZY_BREAK === $element->shouldBreakLazyEvaluation($value)) {
                break;
            }
        }

        if (false === $this->operatorExists($name)) {
            throw new Ruler\Exception\Asserter(
                'Operator %s does not exist.',
                0,
                $name
            );
        }

        $value = $this->getOperator($name)->distributeArguments($arguments);

        return $this->visitDimensions(
            $element,
            $name . '()',
            $value,
            $handle,
            $eldnah
        );
    }

    /**
     * Visit a scalar
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    protected function visitScalar(Ruler\Model\Bag\Scalar $element, &$handle = null, $eldnah = null)
    {
        return $element->getValue();
    }

    /**
     * Visit an array
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  array
     */
    protected function visitArray(Ruler\Model\Bag\RulerArray $element, &$handle = null, $eldnah = null)
    {
        $out = [];

        foreach ($element->getArray() as $key => $data) {
            $out[$key] = $data->accept($this, $handle, $eldnah);
        }

        return $out;
    }

    /**
     * Visit a context
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    protected function visitContext(Ruler\Model\Bag\Context $element, &$handle = null, $eldnah = null)
    {
        $context = $this->getContext();

        if (null === $context) {
            throw new Ruler\Exception\Asserter(
                'Assert needs a context to work properly.',
                1
            );
        }

        $id = $element->getId();

        if (!isset($context[$id])) {
            throw new Ruler\Exception\Asserter(
                'Context reference %s does not exist.',
                2,
                $id
            );
        }

        return $this->visitDimensions(
            $element,
            $id,
            $context[$id],
            $handle,
            $eldnah
        );
    }

    /**
     * Visit dimensions of a context.
     *
     * @param   \Hoa\Visitor\Element  $element      Element to visit.
     * @param   string                $elementId    Element name.
     * @param   mixed                 $subject      Current root to apply the dimensions.
     * @param   mixed                 &$handle      Handle (reference).
     * @param   mixed                 $eldnah       Handle (not reference).
     * @return  mixed
     */
    protected function visitDimensions(
        Ruler\Model\Bag\Context $element,
        $elementId,
        $subject,
        &$handle = null,
        $eldnah  = null
    ) {
        $pointer = $subject;

        foreach ($element->getDimensions() as $dimensionNumber => $dimension) {
            ++$dimensionNumber;

            switch ($dimension[Ruler\Model\Bag\Context::ACCESS_TYPE]) {
                case Ruler\Model\Bag\Context::ARRAY_ACCESS:
                    $this->visitArrayDimension(
                        $pointer,
                        $dimension,
                        $dimensionNumber,
                        $elementId,
                        $handle,
                        $eldnah
                    );

                    break;

                case Ruler\Model\Bag\Context::ATTRIBUTE_ACCESS:
                    $this->visitAttributeDimension(
                        $pointer,
                        $dimension,
                        $dimensionNumber,
                        $elementId,
                        $handle,
                        $eldnah
                    );

                    break;

                case Ruler\Model\Bag\Context::METHOD_ACCESS:
                    $this->visitMethodDimension(
                        $pointer,
                        $dimension,
                        $dimensionNumber,
                        $elementId,
                        $handle,
                        $eldnah
                    );

                    break;
            }
        }

        return $pointer;
    }

    /**
     * Visit an array dimension.
     *
     * @param   mixed   &$contextPointer    Pointer to the current context.
     * @param   array   $dimension          Dimension bucket.
     * @param   int     $dimensionNumber    Dimension number.
     * @param   string  $elementId          Element name.
     * @param   mixed   &$handle            Handle (reference).
     * @param   mixed   $eldnah             Handle (not reference).
     * @return  void
     */
    protected function visitArrayDimension(
        &$contextPointer,
        array $dimension,
        $dimensionNumber,
        $elementId,
        &$handle = null,
        $eldnah  = null
    ) {
        $value = $dimension[Ruler\Model\Bag\Context::ACCESS_VALUE];
        $key   = $value->accept($this, $handle, $eldnah);

        $isArray     = is_array($contextPointer);
        $isArrayLike = !$isArray && $contextPointer instanceof ArrayAccess;

        if (false === $isArray && false === $isArrayLike) {
            throw new Ruler\Exception\Asserter(
                'Try to access to an undefined index: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an array.',
                3,
                [$key, $dimensionNumber, $elementId]
            );
        }

        if ((true === $isArray     && false === array_key_exists($key, $contextPointer)) ||
            (true === $isArrayLike && false === $contextPointer->offsetExists($key))) {
            throw new Ruler\Exception\Asserter(
                'Try to access to an undefined index: %s ' .
                '(dimension number %d of %s).',
                4,
                [$key, $dimensionNumber, $elementId]
            );
        }

        $contextPointer = $contextPointer[$key];

        return;
    }


    /**
     * Visit an attribute dimension.
     *
     * @param   mixed   &$contextPointer    Pointer to the current context.
     * @param   array   $dimension          Dimension bucket.
     * @param   int     $dimensionNumber    Dimension number.
     * @param   string  $elementId          Element name.
     * @param   mixed   &$handle            Handle (reference).
     * @param   mixed   $eldnah             Handle (not reference).
     * @return  void
     */
    protected function visitAttributeDimension(
        &$contextPointer,
        array $dimension,
        $dimensionNumber,
        $elementId,
        &$handle = null,
        $eldnah  = null)
    {
        $attribute = $dimension[Ruler\Model\Bag\Context::ACCESS_VALUE];

        if (!is_object($contextPointer)) {
            throw new Ruler\Exception\Asserter(
                'Try to read an undefined attribute: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an object.',
                5,
                [$attribute, $dimensionNumber, $elementId]
            );
        }

        if (!property_exists($contextPointer, $attribute)) {
            throw new Ruler\Exception\Asserter(
                'Try to read an undefined attribute: %s ' .
                '(dimension number %d of %s).',
                6,
                [$attribute, $dimensionNumber, $elementId]
            );
        }

        $contextPointer = $contextPointer->$attribute;

        return;
    }

    /**
     * Visit a method dimension.
     *
     * @param   mixed   &$contextPointer    Pointer to the current context.
     * @param   array   $dimension          Dimension bucket.
     * @param   int     $dimensionNumber    Dimension number.
     * @param   string  $elementId          Element name.
     * @param   mixed   &$handle            Handle (reference).
     * @param   mixed   $eldnah             Handle (not reference).
     * @return  void
     */
    protected function visitMethodDimension(
        &$contextPointer,
        array $dimension,
        $dimensionNumber,
        $elementId,
        &$handle = null,
        $eldnah  = null
    ) {
        $value  = $dimension[Ruler\Model\Bag\Context::ACCESS_VALUE];
        $method = $value->getName();

        if (!is_object($contextPointer)) {
            throw new Ruler\Exception\Asserter(
                'Try to call an undefined method: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an object.',
                7,
                [$method, $dimensionNumber, $elementId]
            );
        }

        if (!method_exists($contextPointer, $method)) {
            throw new Ruler\Exception\Asserter(
                'Try to call an undefined method: %s ' .
                '(dimension number %d of %s).',
                8,
                [$method, $dimensionNumber, $elementId]
            );
        }

        $arguments = [];

        foreach ($value->getArguments() as $argument) {
            $arguments[] = $argument->accept($this, $handle, $eldnah);
        }

        $contextPointer = call_user_func_array(
            [$contextPointer, $method],
            $arguments
        );

        return;
    }

    /**
     * Set context.
     *
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  \Hoa\Ruler\Context
     */
    public function setContext(Ruler\Context $context)
    {
        $old            = $this->_context;
        $this->_context = $context;

        return $old;
    }

    /**
     * Get context.
     *
     * @return  \Hoa\Ruler\Context
     */
    public function getContext()
    {
        return $this->_context;
    }

    /**
     * Set an operator.
     *
     * @param   string    $operator    Operator.
     * @param   callable  $callable    Callable.
     * @return  Ruler\Visitor\Asserter
     */
    public function setOperator($operator, $callable)
    {
        $this->_operators[$operator] = $callable;

        return $this;
    }

    /**
     * Check if an operator exists.
     *
     * @param   string  $operator    Operator.
     * @return  bool
     */
    public function operatorExists($operator)
    {
        return true === array_key_exists($operator, $this->_operators);
    }

    /**
     * Get an operator.
     *
     * @param   string  $operator    Operator.
     * @return  string
     */
    public function getOperator($operator)
    {
        if (false === $this->operatorExists($operator)) {
            return null;
        }

        $handle = &$this->_operators[$operator];

        if (!($handle instanceof Consistency\Xcallable)) {
            $handle = xcallable($handle);
        }

        return $this->_operators[$operator];
    }

    /**
     * Get all operators.
     *
     * @return  array
     */
    public function getOperators()
    {
        foreach ($this->_operators as &$operator) {
            if (!($operator instanceof Consistency\Xcallable)) {
                $operator = xcallable($operator);
            }
        }

        return $this->_operators;
    }
}
