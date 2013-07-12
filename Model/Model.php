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
 * \Hoa\Ruler\Model\Operator
 */
-> import('Ruler.Model.Operator')

/**
 * \Hoa\Ruler\Model\Bag\Context
 */
-> import('Ruler.Model.Bag.Context')

/**
 * \Hoa\Ruler\Visitor\Compiler
 */
-> import('Ruler.Visitor.Compiler')

/**
 * \Hoa\Visitor\Element
 */
-> import('Visitor.Element');

}

namespace Hoa\Ruler\Model {

/**
 * Class \Hoa\Ruler\Model.
 *
 * Root of the model, allow to declare everything.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Model implements \Hoa\Visitor\Element {

    /**
     * Root.
     *
     * @var \Hoa\Ruler\Model\Operator object
     */
    protected $_root            = null;

    /**
     * Compiler.
     *
     * @var \Hoa\Ruler\Visitor\Compiler object
     */
    protected static $_compiler = null;



    /**
     * Set the expression with $name = 'expression'.
     *
     * @access  public
     * @param   string  $name     Name.
     * @param   mixed   $value    Value.
     * @return  void
     */
    public function __set ( $name, $value ) {

        if('expression' !== $name)
            return $this->$name = $value;

        $this->_root = $value;

        return;
    }

    /**
     * Get the expression.
     *
     * @access  public
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function getExpression ( ) {

        return $this->_root;
    }

    /**
     * Declare a function.
     *
     * @access  public
     * @param   string  $name         Name.
     * @param   mixed   …
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function func ( ) {

        $arguments = func_get_args();
        $name      = array_shift($arguments);

        return $this->_operator($name, $arguments, true);
    }

    /**
     * Declare an operation.
     *
     * @access  public
     * @param   string  $name         Name.
     * @param   array   $arguments    Arguments.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function operation ( $name, Array $arguments ) {

        return $this->_operator($name, $arguments, false);
    }

    /**
     * Create an operator object.
     *
     * @access  public
     * @param   string  $name          Name.
     * @param   array   $arguments     Arguments.
     * @param   bool    $isFunction    Whether it is a function or not.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function _operator ( $name, Array $arguments, $isFunction ) {

        return new Operator(mb_strtolower($name), $arguments, $isFunction);
    }

    /**
     * Declare an operation.
     *
     * @access  public
     * @param   string  $name         Name.
     * @param   array   $arguments    Arguments.
     * @return  \Hoa\Ruler\Model\Operator
     */
    public function __call ( $name, Array $arguments ) {

        return $this->operation($name, $arguments);
    }

    /**
     * Declare a variable.
     *
     * @access  public
     * @parma   string  $id    ID.
     * @return  \Hoa\Ruler\Model\Bag\Context
     */
    public function variable ( $id ) {

        return new Bag\Context($id);
    }

    /**
     * Accept a visitor.
     *
     * @access  public
     * @param   \Hoa\Visitor\Visit  $visitor    Visitor.
     * @param   mixed               &$handle    Handle (reference).
     * @param   mixed               $eldnah     Handle (no reference).
     * @return  mixed
     */
    public function accept ( \Hoa\Visitor\Visit $visitor,
                             &$handle = null, $eldnah = null ) {

        return $visitor->visit($this, $handle, $eldnah);
    }

    /**
     * Transform the object as a string.
     *
     * @access  public
     * @return  string
     */
    public function __toString ( ) {

        if(null === static::$_compiler)
            static::$_compiler = new \Hoa\Ruler\Visitor\Compiler();

        return static::$_compiler->visit($this);
    }
}

}
