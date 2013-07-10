<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2013, Ivan Enderlin. All rights reserved.
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
 * @copyright  Copyright © 2007-2013 Stéphane Py, Ivan Enderlin.
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

        $namespace = 'Hoa\Ruler\Operator\\';
        $this->setOperator('and',  $namespace . '_And');
        $this->setOperator('or',   $namespace . '_Or');
        $this->setOperator('xor',  $namespace . '_Xor');
        $this->setOperator('not',  $namespace . '_Not');
        $this->setOperator('=',    $namespace . 'Equal');
        $this->setOperator('is',   $namespace . 'Equal');
        $this->setOperator('!=',   $namespace . 'NotEqual');
        $this->setOperator('>',    $namespace . 'GreaterThan');
        $this->setOperator('>=',   $namespace . 'GreaterThanOrEqual');
        $this->setOperator('<',    $namespace . 'LessThan');
        $this->setOperator('<=',   $namespace . 'LessThanOrEqual');
        $this->setOperator('in',   $namespace . 'In');
        $this->setOperator('sum',  $namespace . 'Sum');

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
                if($argument instanceof \Hoa\Ruler\Model\Bag)
                    $arguments[] = $argument->transform($this->getContext());
                else
                    $arguments[] = $argument->accept($this, $handle, $eldnah);

            if(false === $this->operatorExists($name))
                throw new \Hoa\Ruler\Exception\Asserter(
                    'Operator %s does not exist.', 1, $name);

            $out = $this->getOperator($name)->distributeArguments($arguments);
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

        if(is_string($handle))
            $handle = xcallable(dnew($handle));
        elseif(!($handle instanceof \Hoa\Core\Consistency\Xcallable))
            $handle = xcallable($handle);

        return $this->_operators[$operator];
    }
}

}
