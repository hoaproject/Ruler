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

namespace Hoa\Ruler\Test\Unit;

use Hoa\Test;
use Hoa\Ruler as LUT;

/**
 * Class \Hoa\Ruler\Test\Unit\Documentation.
 *
 * Test suite of the examples in the documentation.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Ivan Enderlin.
 * @license    New BSD License
 */

class Documentation extends Test\Unit\Suite {

    public function case_classical ( ) {

        $this
            ->given(
                $ruler = new LUT(),
                $rule  = 'group in ["customer", "guest"] and points > 30'
            );

        $this->next_case_classical($ruler, $rule);
    }

    public function next_case_classical ( $ruler, $rule ) {

        $this
            ->given(
                $context           = new LUT\Context(),
                $context['group']  = $this->sample(
                    $this->realdom->regex('/customer|guest/')
                ),
                $context['points'] = function ( ) {

                    return 42;
                }
            )
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isTrue()

            ->given($context['points'] = 29)
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_new_operators ( ) {

        $this
            ->given(
                $user            = new \Mock\StdClass(),
                $user->group     = 'customer',
                $user->points    = 42,
                $user->status    = true,
                $ruler           = new LUT(),
                $rule            = 'logged(user) and group in ["customer", "guest"] and points > 30',
                $context         = new LUT\Context(),
                $context['user'] = function ( ) use ( $user, $context ) {

                    $context['group']  = $user->group;
                    $context['points'] = $user->points;

                    return $user;
                }
            )
            ->when(
                $ruler->getDefaultAsserter()->setOperator('logged', function ( $user ) {

                    return $user->status;
                }),
                $result = $ruler->assert($rule, $context)
            )
            ->then
                ->boolean($result)
                    ->isTrue()

            ->given($user->status = false)
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_interprete ( ) {

        $this
            ->given(
                $model = LUT::interprete('group in ["customer", "guest"] and points > 30')
            )
            ->when($ledom = unserialize(serialize($model)))
            ->then
                ->object($model)
                    ->isEqualTo($ledom);

        $this->next_case_classical(new LUT(), $model);
    }

    public function case_compile ( ) {

        $expectedResult = <<<'RESULT'
$model = new \Hoa\Ruler\Model();
$model->expression =
    $model->and(
        $model->func(
            'logged',
            $model->variable('user')
        ),
        $model->and(
            $model->in(
                $model->variable('group'),
                [
                    'customer',
                    'guest'
                ]
            ),
            $model->{'>'}(
                $model->variable('points'),
                30
            )
        )
    );
RESULT;

        $this
            ->when($result = LUT::interprete(
                'logged(user) and group in ["customer", "guest"] and points > 30'
            ) . '')
            ->then
                ->string($result)
                    ->isEqualTo($expectedResult);
    }

    public function case_not()
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
                )
            )
            ->and($rule = 'not f(false) or g(true)')
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)->isTrue()
                ->boolean($fExecuted)->isTrue()
                ->boolean($gExecuted)->isFalse()
            ;
    }
}
