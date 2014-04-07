<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
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

use Hoa\Core;
use Hoa\Ruler;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Visitor\Asserter.
 *
 * Asserter: evaluate a model representing a rule.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Asserter implements Visitor\Visit {

    /**
     * Context.
     *
     * @var \Hoa\Ruler\Context object
     */
    protected $_context   = null;

    /**
     * List of operators.
     *
     * @var \Hoa\Ruler\Visitor\Asserter array
     */
    protected $_operators = [];



    /**
     * Constructor.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  void
     */
    public function __construct ( Ruler\Context $context = null ) {

        if(null !== $context)
            $this->setContext($context);

        $this->setOperator('and', function ( $a, $b ) { return $a && $b; });
        $this->setOperator('or',  function ( $a, $b ) { return $a || $b; });
        $this->setOperator('xor', function ( $a, $b ) { return (bool) ($a ^ $b); });
        $this->setOperator('not', function ( $a )     { return !$a; });
        $this->setOperator('=',   function ( $a, $b ) { return $a == $b; });
        $this->setOperator('is',  $this->getOperator('='));
        $this->setOperator('!=',  function ( $a, $b ) { return $a != $b; });
        $this->setOperator('>',   function ( $a, $b ) { return $a >  $b; });
        $this->setOperator('>=',  function ( $a, $b ) { return $a >= $b; });
        $this->setOperator('<',   function ( $a, $b ) { return $a <  $b; });
        $this->setOperator('<=',  function ( $a, $b ) { return $a <= $b; });
        $this->setOperator('in',  function ( $a, Array $b ) { return in_array($a, $b); });
        $this->setOperator('sum', function ( ) { return array_sum(func_get_args()); });

        return;
    }

    /**
     * Visit an element.
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     * @throw   \Hoa\Ruler\Exception\Asserter
     */
    public function visit ( Visitor\Element $element, &$handle = null, $eldnah = null ) {

        $context = $this->getContext();

        if(null === $context)
            throw new Ruler\Exeption\Asserter(
                'Assert needs a context to work properly.', 0);

        if($element instanceof Ruler\Model)
            return $this->visitModel($element, $handle, $eldnah);
        elseif($element instanceof Ruler\Model\Operator)
            return $this->visitOperator($element, $handle, $eldnah);
        elseif($element instanceof Ruler\Model\Bag\Scalar)
            return $this->visitScalar($element);
        elseif($element instanceof Ruler\Model\Bag\RulerArray)
            return $this->visitArray($element, $handle, $eldnah);
        elseif($element instanceof Ruler\Model\Bag\Context)
            return $this->visitContext($element, $context, $handle, $eldnah);
    }

    /**
     * Visit a model
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    public function visitModel ( \Hoa\Visitor\Element $element, $handle, $eldnah ) {

        return (bool) $element->getExpression()->accept($this, $handle, $eldnah);
    }

    /**
     * Visit an operator
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    protected function visitOperator ( \Hoa\Visitor\Element $element, $handle, $eldnah ) {

        $name      = $element->getName();
        $arguments = [];

        foreach($element->getArguments() as $argument)
            $arguments[] = $argument->accept($this, $handle, $eldnah);

        if(false === $this->operatorExists($name))
            throw new Ruler\Exception\Asserter(
                'Operator %s does not exist.', 1, $name);

        return $this->getOperator($name)->distributeArguments($arguments);
    }

    /**
     * Visit a scalar
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @return  mixed
     */
    protected function visitScalar ( \Hoa\Visitor\Element $element ) {

        return $element->getValue();
    }

    /**
     * Visit an array
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  array<mixed>
     */
    protected function visitArray ( \Hoa\Visitor\Element $element, $handle, $eldnah ) {

        $out = [];

        foreach($element->getArray() as $key => $data)
            $out[$key] = $data->accept($this, $handle, $eldnah);

        return $out;
    }

    /**
     * Visit a context
     *
     * @access  public
     * @param   \Hoa\Visitor\Element $element    Element to visit.
     * @param   \Hoa\Ruler\Context   $context    Ruler context.
     * @param   mixed                &$handle    Handle (reference).
     * @param   mixed                $eldnah     Handle (not reference).
     * @return  mixed
     */
    protected function visitContext ( \Hoa\Visitor\Element $element, \Hoa\Ruler\Context $context, $handle, $eldnah ) {

        $id = $element->getId();

        if(!isset($context[$id]))
            throw new Ruler\Exception\Asserter(
                'Context reference %s does not exists.', 0, $id);

        $out = $context[$id];

        foreach($element->getDimensions() as $i => $dimension) {

            $value = $dimension[Ruler\Model\Bag\Context::ACCESS_VALUE];

            switch($dimension[Ruler\Model\Bag\Context::ACCESS_TYPE]) {

            case Ruler\Model\Bag\Context::ARRAY_ACCESS:
                return $this->visitContextArray($value, $out, $i, $id, $handle, $eldnah);
                break;

            case Ruler\Model\Bag\Context::ATTRIBUTE_ACCESS:
                return $this->visitContextAttribute($value, $out, $i, $id);
                break;

            case Ruler\Model\Bag\Context::METHOD_ACCESS:
                return $this->visitContextMethod($value, $out, $i, $id, $handle, $eldnah);
                break;
            }
        }

        return $out;
    }

    /**
     * Visit a context array
     *
     * @access  public
     * @param   mixed  $value  value
     * @param   mixed  $out    out
     * @param   mixed  $i      i
     * @param   mixed  $id     id
     * @param   mixed  $handle handle
     * @param   mixed  $eldnah eldnah
     * @return  mixed
     */
    protected function visitContextArray ( $value, $out, $i, $id, $handle, $eldnah ) {

        $key = $value->accept($this, $handle, $eldnah);

        if(!is_array($out))
            throw new Ruler\Exception\Asserter(
                'Try to access to an undefined index: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an array.',
                1, [$key, $i +1, $id]);

        if(!isset($out[$key]))
            throw new Ruler\Exception\Asserter(
                'Try to access to an undefined index: %s ' .
                '(dimension number %d of %s).',
                1, [$key, $i + 1, $id]);

        return $out[$key];
    }


    /**
     * Visit a context attribute
     *
     * @access  public
     * @param   mixed  $value  value
     * @param   mixed  $out    out
     * @param   mixed  $i      i
     * @param   mixed  $id     id
     * @return  mixed
     */
    protected function visitContextAttribute ( $value, $out, $i, $id ) {

        $attribute = $value;

        if(!is_object($out))
            throw new Ruler\Exception\Asserter(
                'Try to read an undefined attribute: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an object.',
                2, [$attribute, $i + 1, $id]);

        if(!property_exists($out, $attribute))
            throw new Ruler\Exception\Asserter(
                'Try to read an undefined attribute: %s ' .
                '(dimension number %d of %s).',
                3, [$attribute, $i + 1, $id]);

        return $out->$attribute;
    }

    /**
     * Visit a context method
     *
     * @access  public
     * @param   mixed  $value  value
     * @param   mixed  $out    out
     * @param   mixed  $i      i
     * @param   mixed  $id     id
     * @param   mixed  $handle handle
     * @param   mixed  $eldnah eldnah
     * @return  mixed
     */
    protected function visitContextMethod ( $value, $out, $i, $id, $handle, $eldnah ) {

        $method = $value->getName();

        if(!is_object($out))
            throw new Ruler\Exception\Asserter(
                'Try to call an undefined method: %s ' .
                '(dimension number %d of %s), because it is ' .
                'not an object.',
                4, [$method, $i + 1, $id]);

        if(!method_exists($out, $method))
            throw new Ruler\Exception\Asserter(
                'Try to call an undefined method: %s ' .
                '(dimension number %d of %s).',
                5, [$method, $i + 1, $id]);

        $arguments = [];

        foreach($value->getArguments() as $argument)
            $arguments[] = $argument->accept($this, $handle, $eldnah);

        return call_user_func_array(
            [$out, $method],
            $arguments
        );
    }

    /**
     * Set context.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  \Hoa\Ruler\Context
     */
    public function setContext ( Ruler\Context $context ) {

        $old            = $this->_context;
        $this->_context = $context;

        return $old;
    }

    /**
     * Get context.
     *
     * @access  public
     * @return  \Hoa\Ruler\Context
     */
    public function getContext ( ) {

        return $this->_context;
    }

    /**
     * Set an operator.
     *
     * @access  public
     * @param   string  $operator     Operator.
     * @param   string  $classname    Classname.
     * @return  Ruler\Visitor\Asserter
     */
    public function setOperator ( $operator, $classname ) {

        $this->_operators[$operator] = $classname;

        return $this;
    }

    /**
     * Check if an operator exists.
     *
     * @access  public
     * @param   string  $operator    Operator.
     * @return  bool
     */
    public function operatorExists ( $operator ) {

        return true === array_key_exists($operator, $this->_operators);
    }

    /**
     * Get an operator.
     *
     * @access  public
     * @param   string  $operator    Operator.
     * @return  string
     */
    public function getOperator ( $operator ) {

        if(false === $this->operatorExists($operator))
            return null;

        $handle = &$this->_operators[$operator];

        if(!($handle instanceof Core\Consistency\Xcallable))
            $handle = xcallable($handle);

        return $this->_operators[$operator];
    }

    /**
     * Get all operators.
     *
     * @access  public
     * @return  array
     */
    public function getOperators ( ) {

        foreach($this->_operators as &$operator)
            if(!($operator instanceof Core\Consistency\Xcallable))
                $operator = xcallable($operator);

        return $this->_operators;
    }
}
