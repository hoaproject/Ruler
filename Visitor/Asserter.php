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

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Exception\Asserter
 */
-> import('Ruler.Exception.Asserter')

/**
 * \Hoa\Ruler\Model\Bag\Context
 */
-> import('Ruler.Model.Bag.Context')

/**
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

}

namespace Hoa\Ruler\Visitor {

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

class Asserter implements \Hoa\Visitor\Visit {

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
    protected $_operators = array();



    /**
     * Constructor.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  void
     */
    public function __construct ( \Hoa\Ruler\Context $context = null ) {

        if(null !== $context)
            $this->setContext($context);

        $this->setOperator('and', function ( $a, $b ) { return $a && $b; });
        $this->setOperator('or',  function ( $a, $b ) { return $a || $b; });
        $this->setOperator('xor', function ( $a, $b ) { return (bool) ($a ^ $b); });
        $this->setOperator('not', function ( $a )     { return !$a; });
        $this->setOperator('+',   function ( $a, $b, $f = 1 ) { return $a + $b * $f; });
        $this->setOperator('-',   function ( $a, $b = null, $f = 1 ) { if(null === $b) return -$a; return $a - $b * $f; });
        $this->setOperator('*',   function ( $a, $b ) { return $a * $b; });
        $this->setOperator('/',   function ( $a, $b ) { return $a / $b; });
        $this->setOperator('**',  function ( $a, $b ) { return pow($a, $b); });
        $this->setOperator('%',   function ( $a, $b ) { return $a % $b; });
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
    public function visit ( \Hoa\Visitor\Element $element, &$handle = null, $eldnah = null ) {

        $context = $this->getContext();

        if(null === $context)
            throw new \Hoa\Ruler\Exeption\Asserter(
                'Assert needs a context to work properly.', 0);

        $out = null;

        if($element instanceof \Hoa\Ruler\Model)
            $out = (bool) $element->getExpression()->accept($this, $handle, $eldnah);
        elseif($element instanceof \Hoa\Ruler\Model\Operator) {

            $name      = $element->getName();
            $arguments = array();

            foreach($element->getArguments() as $argument)
                $arguments[] = $argument->accept($this, $handle, $eldnah);

            if(false === $this->operatorExists($name))
                throw new \Hoa\Ruler\Exception\Asserter(
                    'Operator %s does not exist.', 1, $name);

            $out = $this->getOperator($name)->distributeArguments($arguments);
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Scalar)
            $out = $element->getValue();
        elseif($element instanceof \Hoa\Ruler\Model\Bag\RulerArray) {

            $out = array();

            foreach($element->getArray() as $key => $data)
                $out[$key] = $data->accept($this, $handle, $eldnah);
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Context) {

            $id = $element->getId();

            if(!isset($context[$id]))
                throw new \Hoa\Ruler\Exception\Asserter(
                    'Context reference %s does not exists.', 0, $id);

            $_out = $context[$id];

            foreach($element->getDimensions() as $i => $dimension) {

                $value = $dimension[\Hoa\Ruler\Model\Bag\Context::ACCESS_VALUE];

                switch($dimension[\Hoa\Ruler\Model\Bag\Context::ACCESS_TYPE]) {

                    case \Hoa\Ruler\Model\Bag\Context::ARRAY_ACCESS:
                        $key = $value->accept($this, $handle, $eldnah);

                        if(!is_array($_out))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to access to an undefined index: %s ' .
                                '(dimension number %d of %s), because it is ' .
                                'not an array.',
                                1, array($key, $i +1, $id));

                        if(!isset($_out[$key]))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to access to an undefined index: %s ' .
                                '(dimension number %d of %s).',
                                1, array($key, $i + 1, $id));

                        $_out = $_out[$key];
                      break;

                    case \Hoa\Ruler\Model\Bag\Context::ATTRIBUTE_ACCESS:
                        $attribute = $value;

                        if(!is_object($_out))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to read an undefined attribute: %s ' .
                                '(dimension number %d of %s), because it is ' .
                                'not an object.',
                                2, array($attribute, $i + 1, $id));

                        if(!property_exists($_out, $attribute))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to read an undefined attribute: %s ' .
                                '(dimension number %d of %s).',
                                3, array($attribute, $i + 1, $id));

                        $_out = $_out->$attribute;
                      break;

                    case \Hoa\Ruler\Model\Bag\Context::METHOD_ACCESS:
                        $method = $value->getName();

                        if(!is_object($_out))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to call an undefined method: %s ' .
                                '(dimension number %d of %s), because it is ' .
                                'not an object.',
                                4, array($method, $i + 1, $id));

                        if(!method_exists($_out, $method))
                            throw new \Hoa\Ruler\Exception\Asserter(
                                'Try to call an undefined method: %s ' .
                                '(dimension number %d of %s).',
                                5, array($method, $i + 1, $id));

                        $arguments = array();

                        foreach($value->getArguments() as $argument)
                            $arguments[] = $argument->accept($this, $handle, $eldnah);

                        $_out = call_user_func_array(
                            array($_out, $method),
                            $arguments
                        );
                      break;
                }

            }

            $out = $_out;
        }

        return $out;
    }

    /**
     * Set context.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  \Hoa\Ruler\Context
     */
    public function setContext ( \Hoa\Ruler\Context $context ) {

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
     * @return  \Hoa\Ruler\Visitor\Asserter
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

        if(!($handle instanceof \Hoa\Core\Consistency\Xcallable))
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
            if(!($operator instanceof \Hoa\Core\Consistency\Xcallable))
                $operator = xcallable($operator);

        return $this->_operators;
    }
}

}
