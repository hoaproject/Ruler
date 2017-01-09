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

use Hoa\Ruler as LUT;
use Hoa\Ruler\Context as CUT;
use Hoa\Test;

/**
 * Class \Hoa\Ruler\Test\Unit\Context.
 *
 * Test suite of the context.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context extends Test\Unit\Suite
{
    public function case_array_access()
    {
        $this
            ->when($context = new CUT())
            ->then
                ->object($context)
                    ->isInstanceOf('ArrayAccess');
    }

    public function case_exists_set_get()
    {
        $this
            ->given(
                $key     = 'foo',
                $value   = 'bar',
                $context = new CUT()
            )
            ->then
                ->boolean(isset($context[$key]))
                    ->isFalse()

            ->when($context[$key] = $value)
            ->then
                ->boolean(isset($context[$key]))
                    ->isTrue()
                ->string($context[$key])
                    ->isEqualTo($value);
    }

    public function case_exception_when_getting_unspecified_key()
    {
        $this
            ->given($context = new CUT())
            ->exception(function () use ($context) {
                $context['foo'];
            })
                ->isInstanceOf('Hoa\Ruler\Exception');
    }

    public function case_unset()
    {
        $this
            ->given(
                $key     = 'foo',
                $value   = 'bar',
                $context = new CUT()
            )
            ->then
                ->boolean(isset($context[$key]))
                    ->isFalse()

            ->when($context[$key] = $value)
            ->then
                ->boolean(isset($context[$key]))
                    ->isTrue()

            ->when(function () use ($context, $key) {
                unset($context[$key]);
            })
            ->then
                ->boolean(isset($context[$key]))
                    ->isFalse();
    }

    public function case_callable_closure()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = function () {
                    return fakeCallable();
                }
            )
            ->when($result = $context['foo'])
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_callable_user_function()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = __NAMESPACE__ . '\fakeCallable'
            )
            ->when($result = $context['foo'])
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_callable_internal_function()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = 'var_dump'
            )
            ->when($result = $context['foo'])
            ->then
                ->string($result)
                    ->isEqualTo('var_dump');
    }

    public function case_callable_method()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = [$this, 'fakeCallable']
            )
            ->when($result = $context['foo'])
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_callable_xcallable()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = xcallable($this, 'fakeCallable')
            )
            ->when($result = $context['foo'])
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_callable_cache()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = function () {
                    static $i = 0;

                    return $i++;
                }
            )
            ->when($result = $context['foo'])
            ->then
                ->integer($result)
                    ->isEqualTo(0)

            ->when($result = $context['foo'])
            ->then
                ->integer($result)
                    ->isEqualTo(0);
    }

    public function case_callable_no_cache()
    {
        $this
            ->given(
                $context        = new CUT(),
                $context['foo'] = new LUT\DynamicCallable(function () {
                    static $i = 0;

                    return $i++;
                })
            )
            ->when($result = $context['foo'])
            ->then
                ->integer($result)
                    ->isEqualTo(0)

            ->when($result = $context['foo'])
            ->then
                ->integer($result)
                    ->isEqualTo(1);
    }

    public function case_callable_argument()
    {
        $this
            ->given(
                $self           = $this,
                $context        = new CUT(),
                $context['foo'] = function () use ($self, $context) {
                    $arguments = func_get_args();

                    $self
                        ->integer(count($arguments))
                            ->isEqualTo(1)
                        ->object($arguments[0])
                            ->isIdenticalTo($context);
                }
            )
            ->when($result = $context['foo']);
    }

    public function fakeCallable()
    {
        return fakeCallable();
    }
}

function fakeCallable()
{
    return true;
}
