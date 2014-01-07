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
 * \Hoa\Ruler\Context
 */
-> import('Ruler.Context')

/**
 * \Hoa\Ruler\Visitor\Interpreter
 */
-> import('Ruler.Visitor.Interpreter')

/**
 * \Hoa\Ruler\Visitor\Asserter
 */
-> import('Ruler.Visitor.Asserter')

/**
 * \Hoa\Compiler\Llk
 */
-> import('Compiler.Llk.~')

/**
 * \Hoa\File\Read
 */
-> import('File.Read');

}

namespace Hoa\Ruler {

/**
 * Class \Hoa\Ruler.
 *
 * Ruler helpers.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Ruler {

    /**
     * Compiler.
     *
     * @var \Hoa\Compiler\Llk\Parser object
     */
    protected static $_compiler        = null;

    /**
     * Interpreter.
     *
     * @var \Hoa\Ruler\Visitor\Interpreter object
     */
    protected static $_interpreter     = null;

    /**
     * Default asserter.
     *
     * @var \Hoa\Visitor\Visit object
     */
    protected $_asserter               = null;

    /**
     * Default asserter.
     *
     * @var \Hoa\Ruler\Visitor\Asserter object
     */
    protected static $_defaultAsserter = null;



    /**
     * Assert.
     *
     * @access  public
     * @param   mixed                 $rule       Rule (string or model)
     * @param   \Hoa\Ruler\Context    $context    Context.
     * @return  bool
     * @throw   \Hoa\Ruler\Exception
     */
    public function assert ( $rule, Context $context = null ) {

        if(is_string($rule))
            $rule = static::interprete($rule);

        if(null === $context)
            $context = new Context();

        return $this->getAsserter($context)->visit($rule);
    }

    /**
     * Short interpreter.
     *
     * @access  public
     * @param   string  $rule    Rule.
     * @return  \Hoa\Ruler\Model
     * @throw   \Hoa\Ruler\Exception
     */
    public static function interprete ( $rule ) {

        return static::getInterpreter()->visit(
            static::getCompiler()->parse($rule)
        );
    }

    /**
     * Get interpreter.
     *
     * @access  public
     * @return  \Hoa\Ruler\Visitor\Interpreter
     */
    public static function getInterpreter ( ) {

        if(null === static::$_interpreter)
            static::$_interpreter = new Visitor\Interpreter();

        return static::$_interpreter;
    }

    /**
     * Set current asserter.
     *
     * @access  public
     * @param   \Hoa\Visitor\Visit  $visitor    Visitor.
     * @return  \Hoa\Visitor\Visit
     */
    public function setAsserter ( \Hoa\Visitor\Visit $visitor ) {

        $old             = $this->_asserter;
        $this->_asserter = $visitor;

        return $old;
    }

    /**
     * Get asserter.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  \Hoa\Visitor\Visit
     */
    public function getAsserter ( Context $context ) {

        if(null === $asserter = $this->_asserter)
            return static::getDefaultAsserter($context);

        $asserter->setContext($context);

        return $asserter;
    }

    /**
     * Get default asserter.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context    $context    Context.
     * @return  \Hoa\Ruler\Visitor\Asserter
     */
    public static function getDefaultAsserter ( Context $context = null ) {

        if(null === static::$_defaultAsserter)
            static::$_defaultAsserter = new Visitor\Asserter($context);

        if(null !== $context)
            static::$_defaultAsserter->setContext($context);

        return static::$_defaultAsserter;
    }

    /**
     * Get compiler.
     *
     * @access  public
     * @return  \Hoa\Compiler\Llk\Parser
     */
    public static function getCompiler ( ) {

        if(null === static::$_compiler)
            static::$_compiler = \Hoa\Compiler\Llk::load(
                new \Hoa\File\Read('hoa://Library/Ruler/Grammar.pp')
            );

        return static::$_compiler;
    }
}

}

namespace {

/**
 * Flex entity.
 */
Hoa\Core\Consistency::flexEntity('Hoa\Ruler\Ruler');

}
