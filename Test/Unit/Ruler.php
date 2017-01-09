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

namespace Hoa\Ruler\Test\Unit;

use Hoa\Compiler;
use Hoa\Ruler as LUT;
use Hoa\Ruler\Ruler as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Ruler\Test\Unit\Ruler.
 *
 * Test suite of the ruler class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Ruler extends Test\Unit\Suite
{
    public function case_assert()
    {
        $this
            ->given(
                $rule  = '7 < 42',
                $ruler = new SUT()
            )
            ->when($result = $ruler->assert($rule))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_assert_with_a_context()
    {
        $this
            ->given(
                $rule         = 'x < 42',
                $ruler        = new SUT(),
                $context      = new LUT\Context(),
                $context['x'] = 7
            )
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_assert_with_rule_as_a_model()
    {
        $this
            ->given(
                $rule         = SUT::interpret('x < 42'),
                $ruler        = new SUT(),
                $context      = new LUT\Context(),
                $context['x'] = 7
            )
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_interpret()
    {
        $this
            ->when($result = SUT::interpret('x < 42'))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model::class);
    }

    public function case_get_interpreter()
    {
        $this
            ->when($result = SUT::getInterpreter())
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Visitor\Interpreter::class);
    }

    public function case_set_asserter()
    {
        $this
            ->given(
                $ruler    = new SUT(),
                $asserter = new LUT\Visitor\Asserter()
            )
            ->when($result = $ruler->setAsserter($asserter))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_asserter()
    {
        $this
            ->given(
                $asserter = new LUT\Visitor\Asserter(),
                $ruler    = new SUT(),
                $context  = new LUT\Context(),
                $ruler->setAsserter($asserter),
                $asserter->setContext($context)
            )
            ->when($result = $ruler->getAsserter())
            ->then
                ->object($result)
                    ->isIdenticalTo($asserter)
                ->object($result->getContext())
                    ->isIdenticalTo($context);
    }

    public function case_get_asserter_with_a_specific_context()
    {
        $this
            ->given(
                $asserter = new LUT\Visitor\Asserter(),
                $contextA = new LUT\Context(),
                $contextB = new LUT\Context(),
                $ruler    = new SUT(),
                $ruler->setAsserter($asserter),
                $asserter->setContext($contextA)
            )
            ->when($result = $ruler->getAsserter($contextB))
            ->then
                ->object($result)
                    ->isIdenticalTo($asserter)
                ->object($result->getContext())
                    ->isIdenticalTo($contextB);
    }

    public function case_get_asserter_the_default_one()
    {
        $this
            ->given($ruler = new SUT())
            ->when($result = $ruler->getAsserter())
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Visitor\Asserter::class)
                ->variable($result->getContext())
                    ->isNull()
                ->object($ruler->getAsserter())
                    ->isIdenticalTo($result);
    }

    public function case_get_asserter_the_default_one_with_a_specific_context()
    {
        $this
            ->given(
                $ruler   = new SUT(),
                $context = new LUT\Context()
            )
            ->when($result = $ruler->getAsserter($context))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Visitor\Asserter::class)
                ->object($result->getContext())
                    ->isIdenticalTo($context)
                ->object($ruler->getAsserter($context))
                    ->isIdenticalTo($result);
    }

    public function case_get_default_asserter()
    {
        $this
            ->when($result = SUT::getDefaultAsserter())
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Visitor\Asserter::class)
                ->variable($result->getContext())
                    ->isNull()
                ->object(SUT::getDefaultAsserter())
                    ->isIdenticalTo($result);
    }

    public function case_get_default_asserter_with_a_specific_context()
    {
        $this
            ->given($context = new LUT\Context())
            ->when($result = SUT::getDefaultAsserter($context))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Visitor\Asserter::class)
                ->object($result->getContext())
                    ->isIdenticalTo($context)
                ->object(SUT::getDefaultAsserter($context))
                    ->isIdenticalTo($result);
    }

    public function case_get_compiler()
    {
        $this
            ->when($result = SUT::getCompiler())
            ->then
                ->object($result)
                    ->isInstanceOf(Compiler\Llk\Parser::class)
                ->object(SUT::getCompiler())
                    ->isIdenticalTo($result);
    }
}
