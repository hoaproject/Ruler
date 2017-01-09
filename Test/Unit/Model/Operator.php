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

namespace Hoa\Ruler\Test\Unit\Model;

use Hoa\Ruler as LUT;
use Hoa\Ruler\Model\Operator as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Model\Operator.
 *
 * Test suite of the operator object of the model.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Operator extends Test\Unit\Suite
{
    public function case_is_a_visitor_element()
    {
        $this
            ->given($name = 'foo')
            ->when($result = new SUT($name))
            ->then
                ->object($result)
                    ->isInstanceOf(Visitor\Element::class);
    }

    public function case_is_a_context()
    {
        $this
            ->given($name = 'foo')
            ->when($result = new SUT($name))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Bag\Context::class);
    }

    public function case_constructor()
    {
        $this
            ->given($name = 'foo')
            ->when($result = new SUT($name))
            ->then
                ->string($result->getName())
                    ->isEqualTo($name)
                ->array($result->getArguments())
                    ->isEmpty()
                ->boolean($result->isFunction())
                    ->isTrue()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_constructor_with_arguments()
    {
        $this
            ->given(
                $name      = 'foo',
                $arguments = [new LUT\Model\Bag\Scalar(42)]
            )
            ->when($result = new SUT($name, $arguments))
            ->then
                ->string($result->getName())
                    ->isEqualTo($name)
                ->array($result->getArguments())
                    ->isEqualTo($arguments)
                ->boolean($result->isFunction())
                    ->isTrue()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_constructor_with_arguments_and_function_flag()
    {
        $this
            ->given(
                $name       = 'foo',
                $arguments  = [new LUT\Model\Bag\Scalar(42)],
                $isFunction = false
            )
            ->when($result = new SUT($name, $arguments, $isFunction))
            ->then
                ->string($result->getName())
                    ->isEqualTo($name)
                ->array($result->getArguments())
                    ->isEqualTo($arguments)
                ->boolean($result->isFunction())
                    ->isFalse()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_set_name()
    {
        $this
            ->given(
                $oldName  = 'foo',
                $name     = 'bar',
                $operator = new SUT('foo')
            )
            ->when($result = $this->invoke($operator)->setName($name))
            ->then
                ->string($result)
                    ->isEqualTo($oldName)
                ->boolean($operator->isLazy())
                    ->isFalse();
    }

    public function case_set_name_with_the_and_operator_for_auto_laziness()
    {
        return $this->_case_set_name_with_auto_laziness('and');
    }

    public function case_set_name_with_the_or_operator_for_auto_laziness()
    {
        return $this->_case_set_name_with_auto_laziness('or');
    }

    protected function _case_set_name_with_auto_laziness($name)
    {
        $this
            ->given($operator = new SUT('foo'))
            ->when($result = $this->invoke($operator)->setName($name))
            ->then
                ->string($result)
                    ->isEqualTo('foo')
                ->boolean($operator->isLazy())
                    ->isTrue();
    }

    public function case_get_name()
    {
        $this
            ->given(
                $name     = 'bar',
                $operator = new SUT('foo'),
                $this->invoke($operator)->setName($name)
            )
            ->when($result = $operator->getName())
            ->then
                ->string($result)
                    ->isEqualTo($name);
    }

    public function case_set_arguments()
    {
        $this
            ->given(
                $operator  = new SUT('foo'),
                $arguments = ['foo', [42], new LUT\Model\Bag\Scalar('baz')]
            )
            ->when($result = $this->invoke($operator)->setArguments($arguments))
            ->then
                ->array($result)
                    ->isEmpty();
    }

    public function case_set_arguments_not_additive()
    {
        $this
            ->given(
                $operator   = new SUT('foo'),
                $argumentsA = [new LUT\Model\Bag\Scalar('foo')],
                $argumentsB = [new LUT\Model\Bag\Scalar('bar')],
                $this->invoke($operator)->setArguments($argumentsA)
            )
            ->when($result = $this->invoke($operator)->setArguments($argumentsB))
            ->then
                ->array($result)
                    ->isEqualTo($argumentsA)
                ->array($operator->getArguments())
                    ->isEqualTo($argumentsB);
    }

    public function case_get_arguments()
    {
        $this
            ->given(
                $operator  = new SUT('foo'),
                $arguments = ['foo', [42], new LUT\Model\Bag\Scalar('baz')],
                $this->invoke($operator)->setArguments($arguments)
            )
            ->when($result = $operator->getArguments())
            ->then
                ->array($result)
                    ->isEqualTo([
                        new LUT\Model\Bag\Scalar('foo'),
                        new LUT\Model\Bag\RulerArray([42]),
                        new LUT\Model\Bag\Scalar('baz')
                    ]);
    }

    public function case_set_function()
    {
        $this
            ->given($operator = new SUT('foo'))
            ->when($result = $this->invoke($operator)->setFunction(false))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_function()
    {
        $this
            ->given(
                $operator = new SUT('foo'),
                $this->invoke($operator)->setFunction(true)
            )
            ->when($result = $operator->isFunction())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_not_function()
    {
        $this
            ->given(
                $operator = new SUT('foo'),
                $this->invoke($operator)->setFunction(false)
            )
            ->when($result = $operator->isFunction())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_set_laziness()
    {
        $this
            ->given($operator = new SUT('foo'))
            ->when($result = $this->invoke($operator)->setLaziness(false))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_is_lazy()
    {
        $this
            ->given(
                $operator = new SUT('foo'),
                $this->invoke($operator)->setLaziness(true)
            )
            ->when($result = $operator->isLazy())
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_is_not_lazy()
    {
        $this
            ->given(
                $operator = new SUT('foo'),
                $this->invoke($operator)->setLaziness(false)
            )
            ->when($result = $operator->isLazy())
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_should_break_lazy_evaluation_with_and_operator()
    {
        return $this->_case_should_break_lazy_evaluation_with_x_operator(
            'and',
            false,
            SUT::LAZY_BREAK
        );
    }

    public function case_should_not_break_lazy_evaluation_with_and_operator()
    {
        return $this->_case_should_break_lazy_evaluation_with_x_operator(
            'and',
            true,
            SUT::LAZY_CONTINUE
        );
    }

    public function case_should_break_lazy_evaluation_with_or_operator()
    {
        return $this->_case_should_break_lazy_evaluation_with_x_operator(
            'or',
            true,
            SUT::LAZY_BREAK
        );
    }

    public function case_should_not_break_lazy_evaluation_with_or_operator()
    {
        return $this->_case_should_break_lazy_evaluation_with_x_operator(
            'or',
            false,
            SUT::LAZY_CONTINUE
        );
    }

    public function case_should_not_break_lazy_evaluation_with_any_operator()
    {
        return $this->_case_should_break_lazy_evaluation_with_x_operator(
            'foo',
            42,
            SUT::LAZY_CONTINUE
        );
    }

    protected function _case_should_break_lazy_evaluation_with_x_operator($name, $value, $expect)
    {
        $this
            ->given($operator = new SUT($name))
            ->when($result = $operator->shouldBreakLazyEvaluation($value))
            ->then
                ->boolean($result)
                    ->isEqualTo($expect);
    }

    public function case_is_token()
    {
        $this
            ->when(function () {
                foreach (['not', 'and', 'or', 'xor'] as $token) {
                    $this
                        ->when($result = SUT::isToken($token))
                        ->then
                            ->boolean($result)
                                ->isTrue();
                }
            });
    }

    public function case_is_not_token()
    {
        $this
            ->when($result = SUT::isToken('foo'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }
}
