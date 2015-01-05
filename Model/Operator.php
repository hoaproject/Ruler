<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Ivan Enderlin. All rights reserved.
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

use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Model\Operator.
 *
 * Represent an operator or a function (in prefixed notation).
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2015 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Operator implements Visitor\Element {

    /**
     * Name.
     *
     * @var \Hoa\Ruler\Model\Operator string
     */
    protected $_name      = null;

    /**
     * Arguments.
     *
     * @var \Hoa\Ruler\Model\Operator array
     */
    protected $_arguments = null;

    /**
     * Whether the operator is a function.
     *
     * @var \Hoa\Ruler\Model\Operator bool
     */
    protected $_function  = true;



    /**
     * Constructor.
     *
     * @access  public
     * @param   string  $name          Name.
     * @param   array   $arguments     Arguments.
     * @param   bool    $isFunction    Whether it is a function.
     * @return  void
     */
    public function __construct ( $name, Array $arguments = [],
                                  $isFunction = true ) {

        $this->setName($name);
        $this->setArguments($arguments);
        $this->setFunction($isFunction);

        return;
    }

    /**
     * Set name.
     *
     * @access  protected
     * @param   string  $name    Name.
     * @return  string
     */
    protected function setName ( $name ) {

        $old         = $this->_name;
        $this->_name = $name;

        return $old;
    }

    /**
     * Get name.
     *
     * @access  public
     * @return  string
     */
    public function getName ( ) {

        return $this->_name;
    }

    /**
     * Set arguments.
     *
     * @access  protected
     * @param   array  $arguments    Arguments.
     * @return  array
     */
    protected function setArguments ( Array $arguments ) {

        foreach($arguments as &$argument)
            if(is_scalar($argument) || null === $argument)
                $argument = new Bag\Scalar($argument);
            elseif(is_array($argument))
                $argument = new Bag\RulerArray($argument);

        $old              = $this->_arguments;
        $this->_arguments = $arguments;

        return $old;
    }

    /**
     * Get arguments.
     *
     * @access  public
     * @return  array
     */
    public function getArguments ( ) {

        return $this->_arguments;
    }

    /**
     * Set whether the operator is a function or not.
     *
     * @access  public
     * @param   bool  $isFunction    Is a function or not.
     * @return  bool
     */
    protected function setFunction ( $isFunction ) {

        $old             = $this->_function;
        $this->_function = $isFunction;

        return $old;
    }

    /**
     * Check if the operator is a function or not.
     *
     * @access  public
     * @return  bool
     */
    public function isFunction ( ) {

        return $this->_function;
    }

    /**
     * Check if the operator is a token of the grammar or not.
     *
     * @access  public
     * @param   string  $operator    Operator.
     * @return  bool
     */
    public static function isToken ( $operator ) {

        static $_tokens = ['not', 'and', 'or', 'xor'];

        return true === in_array($operator, $_tokens);
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
    public function accept ( Visitor\Visit $visitor,
                             &$handle = null, $eldnah = null ) {

        return $visitor->visit($this, $handle, $eldnah);
    }
}
