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

namespace Hoa\Ruler\Model;

use Hoa\Consistency;
use Hoa\Ruler;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Model.
 *
 * Root of the model, allow to declare everything.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Model implements Visitor\Element
{
    /**
     * Root.
     *
     * @var \Hoa\Ruler\Model\Operator
     */
    protected $_root            = null;

    /**
     * Compiler.
     *
     * @var \Hoa\Ruler\Visitor\Compiler
     */
    protected static $_compiler = null;



    /**
     * Set the expression with $name = 'expression'.
     *
     * @param   string  $name     Name.
     * @param   mixed   $value    Value.
     * @return  void
     */
    public function __set($name, $value)
    {
        if ('expression' !== $name) {
            return $this->$name = $value;
        }

        if (is_scalar($value)) {
            $value = new Bag\Scalar($value);
        } elseif (is_array($value)) {
            $value = new Bag\RulerArray($value);
        }

        $this->_root = $value;

        return;
    }

    /**
     * Get the expression.
     *
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function getExpression()
    {
        return $this->_root;
    }

    /**
     * Declare a function.
     *
     * @param   string  $name         Name.
     * @param   mixed   …
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function func()
    {
        $arguments = func_get_args();
        $name      = array_shift($arguments);

        return $this->_operator($name, $arguments, true);
    }

    /**
     * Declare an operation.
     *
     * @param   string  $name         Name.
     * @param   array   $arguments    Arguments.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function operation($name, array $arguments)
    {
        return $this->_operator($name, $arguments, false);
    }

    /**
     * Create an operator object.
     *
     * @param   string  $name          Name.
     * @param   array   $arguments     Arguments.
     * @param   bool    $isFunction    Whether it is a function or not.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function _operator($name, array $arguments, $isFunction)
    {
        return new Operator(mb_strtolower($name), $arguments, $isFunction);
    }

    /**
     * Declare an operation.
     *
     * @param   string  $name         Name.
     * @param   array   $arguments    Arguments.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function __call($name, array $arguments)
    {
        return $this->operation($name, $arguments);
    }

    /**
     * Declare a variable.
     *
     * @parma   string  $id    ID.
     * @return  \Hoa\Ruler\Model\Bag\Context
     */
    public function variable($id)
    {
        return new Bag\Context($id);
    }

    /**
     * Accept a visitor.
     *
     * @param   \Hoa\Visitor\Visit  $visitor    Visitor.
     * @param   mixed               &$handle    Handle (reference).
     * @param   mixed               $eldnah     Handle (no reference).
     * @return  mixed
     */
    public function accept(
        Visitor\Visit $visitor,
        &$handle = null,
        $eldnah  = null
    ) {
        return $visitor->visit($this, $handle, $eldnah);
    }

    /**
     * Transform the object as a string.
     *
     * @return  string
     */
    public function __toString()
    {
        if (null === static::$_compiler) {
            static::$_compiler = new Ruler\Visitor\Compiler();
        }

        return static::$_compiler->visit($this);
    }
}

/**
 * Flex entity.
 */
Consistency::flexEntity('Hoa\Ruler\Model\Model');
