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
use Hoa\Ruler\Model as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Model\Model.
 *
 * Test suite of the model root object.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Model extends Test\Unit\Suite
{
    public function case_is_a_visitor_element()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->object($result)
                    ->isInstanceOf(Visitor\Element::class);
    }

    public function case_set_default()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->foo = 'bar')
            ->then
                ->string($result)
                    ->isEqualTo('bar')
                ->string($model->foo)
                    ->isEqualTo('bar');
    }

    public function case_set_expression()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->expression = 'foo')
            ->then
                ->string($result)
                    ->isEqualTo('foo')
                ->boolean(isset($model->expression))
                    ->isFalse();
    }

    public function case_get_expression_is_a_scalar()
    {
        $this
            ->given(
                $model = new SUT(),
                $model->expression = 'foo'
            )
            ->when($result = $model->getExpression())
            ->then
                ->object($result)
                    ->isEqualTo(new LUT\Model\Bag\Scalar('foo'));
    }

    public function case_get_expression_is_an_array()
    {
        $this
            ->given(
                $model = new SUT(),
                $model->expression = ['foo']
            )
            ->when($result = $model->getExpression())
            ->then
                ->object($result)
                    ->isEqualTo(new LUT\Model\Bag\RulerArray(['foo']));
    }

    public function case_get_expression()
    {
        $this
            ->given(
                $model = new SUT(),
                $model->expression = new LUT\Model\Operator('f')
            )
            ->when($result = $model->getExpression())
            ->then
                ->object($result)
                    ->isEqualTo(new LUT\Model\Operator('f'));
    }

    public function case_func()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->func('f', 'x', 42))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Operator::class)
                ->string($result->getName())
                    ->isEqualTo('f')
                ->array($result->getArguments())
                    ->isEqualTo([
                        new LUT\Model\Bag\Scalar('x'),
                        new LUT\Model\Bag\Scalar(42)
                    ])
                ->boolean($result->isFunction())
                    ->isTrue()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_operation()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->operation('f', ['x', 42]))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Operator::class)
                ->string($result->getName())
                    ->isEqualTo('f')
                ->array($result->getArguments())
                    ->isEqualTo([
                        new LUT\Model\Bag\Scalar('x'),
                        new LUT\Model\Bag\Scalar(42)
                    ])
                ->boolean($result->isFunction())
                    ->isFalse()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_operator()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->_operator('f', ['x', 42], true))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Operator::class)
                ->string($result->getName())
                    ->isEqualTo('f')
                ->array($result->getArguments())
                    ->isEqualTo([
                        new LUT\Model\Bag\Scalar('x'),
                        new LUT\Model\Bag\Scalar(42)
                    ])
                ->boolean($result->isFunction())
                    ->isTrue()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_call()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->f('x', 42))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Operator::class)
                ->string($result->getName())
                    ->isEqualTo('f')
                ->array($result->getArguments())
                    ->isEqualTo([
                        new LUT\Model\Bag\Scalar('x'),
                        new LUT\Model\Bag\Scalar(42)
                    ])
                ->boolean($result->isFunction())
                    ->isFalse()
                ->boolean($result->isLazy())
                    ->isFalse();
    }

    public function case_variable()
    {
        $this
            ->given($model = new SUT())
            ->when($result = $model->variable('x'))
            ->then
                ->object($result)
                    ->isEqualTo(new LUT\Model\Bag\Context('x'));
    }

    public function case_to_string()
    {
        $this
            ->given(
                $model = new SUT(),
                $model->expression = $model->f('x', 42)
            )
            ->when($result = $model->__toString())
            ->then
                ->string($result)
                ->isEqualTo(
                    '$model = new \Hoa\Ruler\Model();' . "\n" .
                    '$model->expression =' . "\n" .
                    '    $model->f(' . "\n" .
                    '        \'x\',' . "\n" .
                    '        42' . "\n" .
                    '    );'
                );
    }
}
