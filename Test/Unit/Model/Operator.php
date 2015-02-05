<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Hoa community. All rights reserved.
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

use Hoa\Test;
use Hoa\Ruler as LUT;

/**
 * Class \Hoa\Ruler\Test\Unit\Documentation.
 *
 * Test suite of the examples in the documentation.
 *
 * @author     Hoa community <avonglasow@hoa-project.net>
 * @copyright  Copyright © 2007-2015 Hoa community.
 * @license    New BSD License
 */

class Operator extends Test\Unit\Suite {

    public function case_and_lazyOperator()
    {
        $this
            ->given(
                $ruler     = new LUT(),
                $fExecuted = false,
                $gExecuted = false,
                $ruler->getDefaultAsserter()->setOperator(
                    'f',
                    function ( $a = false ) use ( &$fExecuted ) {
                        $fExecuted = true;
                        return $a;
                    }
                ),
                $ruler->getDefaultAsserter()->setOperator(
                    'g',
                    function ( $b = false ) use ( &$gExecuted ) {
                        $gExecuted = true;
                        return $b;
                    }
                ),
                $rule = 'f(false) and g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isFalse()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) and g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) and g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(false) and g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isFalse()
            ;
    }

    public function case_or_lazyOperator()
    {
        $this
            ->given(
                $ruler     = new LUT(),
                $fExecuted = false,
                $gExecuted = false,
                $ruler->getDefaultAsserter()->setOperator(
                    'f',
                    function ( $a ) use ( &$fExecuted ) {
                        $fExecuted = true;
                        return $a;
                    }
                ),
                $ruler->getDefaultAsserter()->setOperator(
                    'g',
                    function ( $b ) use ( &$gExecuted ) {
                        $gExecuted = true;
                        return $b;
                    }
                ),
                $rule = 'f(false) or g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) or g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isFalse()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) or g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isFalse()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(false) or g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ;
    }

    public function case_xor()
    {
        $this
            ->given(
                $ruler     = new LUT(),
                $fExecuted = false,
                $gExecuted = false,
                $ruler->getDefaultAsserter()->setOperator(
                    'f',
                    function ( $a = false ) use ( &$fExecuted ) {
                        $fExecuted = true;
                        return $a;
                    }
                ),
                $ruler->getDefaultAsserter()->setOperator(
                    'g',
                    function ( $b = false ) use ( &$gExecuted ) {
                        $gExecuted = true;
                        return $b;
                    }
                ),
                $rule = 'f(false) xor g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) xor g(true)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(true) xor g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ->given(
                $fExecuted = false,
                $gExecuted = false,
                $rule = 'f(false) xor g(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($gExecuted)
                    ->isTrue()
            ;
    }
}
