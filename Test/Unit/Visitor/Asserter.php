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

namespace Hoa\Ruler\Test\Unit\Visitor;

use ArrayObject;
use Hoa\Ruler as LUT;
use Hoa\Ruler\Visitor\Asserter as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Visitor\Asserter.
 *
 * Test suite of the compiler visitor.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Asserter extends Test\Unit\Suite
{
    public function case_is_a_visitor()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->object($result)
                    ->isInstanceOf(Visitor\Visit::class);
    }

    public function case_constructor()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->array($result->getOperators())
                    ->hasSize(14)
                    ->hasKey('and')
                    ->hasKey('or')
                    ->hasKey('xor')
                    ->hasKey('not')
                    ->hasKey('=')
                    ->hasKey('is')
                    ->hasKey('!=')
                    ->hasKey('>')
                    ->hasKey('>=')
                    ->hasKey('<')
                    ->hasKey('<=')
                    ->hasKey('in')
                    ->hasKey('sum')
                    ->hasKey('matches')
                ->variable($result->getContext())
                    ->isNull();
    }

    public function case_constructor_with_a_context()
    {
        $this
            ->given($context = new LUT\Context())
            ->when($result = new SUT($context))
            ->then
                ->object($result->getContext())
                    ->isIdenticalTo($context);
    }

    public function case_operator_and_arity_0()
    {
        return $this->_case_boolean_operator('and', [], false);
    }

    public function case_operator_and_true()
    {
        return $this->_case_boolean_operator('and', [true], false);
    }

    public function case_operator_and_false()
    {
        return $this->_case_boolean_operator('and', [false], false);
    }

    public function case_operator_and_true_true()
    {
        return $this->_case_boolean_operator('and', [true, true], true);
    }

    public function case_operator_and_true_false()
    {
        return $this->_case_boolean_operator('and', [true, false], false);
    }

    public function case_operator_and_false_true()
    {
        return $this->_case_boolean_operator('and', [false, true], false);
    }

    public function case_operator_and_false_false()
    {
        return $this->_case_boolean_operator('and', [false, false], false);
    }

    public function case_operator_or_arity_0()
    {
        return $this->_case_boolean_operator('or', [], false);
    }

    public function case_operator_or_true()
    {
        return $this->_case_boolean_operator('or', [true], true);
    }

    public function case_operator_or_false()
    {
        return $this->_case_boolean_operator('or', [false], false);
    }

    public function case_operator_or_true_true()
    {
        return $this->_case_boolean_operator('or', [true, true], true);
    }

    public function case_operator_or_true_false()
    {
        return $this->_case_boolean_operator('or', [true, false], true);
    }

    public function case_operator_or_false_true()
    {
        return $this->_case_boolean_operator('or', [false, true], true);
    }

    public function case_operator_or_false_false()
    {
        return $this->_case_boolean_operator('or', [false, false], false);
    }

    public function case_operator_xor_true_true()
    {
        return $this->_case_boolean_operator('xor', [true, true], false);
    }

    public function case_operator_xor_true_false()
    {
        return $this->_case_boolean_operator('xor', [true, false], true);
    }

    public function case_operator_xor_false_true()
    {
        return $this->_case_boolean_operator('xor', [false, true], true);
    }

    public function case_operator_xor_false_false()
    {
        return $this->_case_boolean_operator('xor', [false, false], false);
    }

    public function case_operator_not_true()
    {
        return $this->_case_boolean_operator('not', [true], false);
    }

    public function case_operator_not_false()
    {
        return $this->_case_boolean_operator('not', [false], true);
    }

    public function case_operator_equal_7_42()
    {
        return $this->_case_boolean_operator('=', [7, 42], false);
    }

    public function case_operator_equal_7_7()
    {
        return $this->_case_boolean_operator('=', [7, 7], true);
    }

    public function case_operator_equal_7_7_casting()
    {
        return $this->_case_boolean_operator('=', [7, '7'], true);
    }

    public function case_operator_is_7_42()
    {
        return $this->_case_boolean_operator('is', [7, 42], false);
    }

    public function case_operator_is_7_7()
    {
        return $this->_case_boolean_operator('is', [7, 7], true);
    }

    public function case_operator_is_7_7_casting()
    {
        return $this->_case_boolean_operator('is', [7, '7'], true);
    }

    public function case_operator_not_equal_7_42()
    {
        return $this->_case_boolean_operator('!=', [7, 42], true);
    }

    public function case_operator_not_equal_7_7()
    {
        return $this->_case_boolean_operator('!=', [7, 7], false);
    }

    public function case_operator_not_equal_7_7_casting()
    {
        return $this->_case_boolean_operator('!=', [7, '7'], false);
    }

    public function case_operator_greater_than_7_42()
    {
        return $this->_case_boolean_operator('>', [7, 42], false);
    }

    public function case_operator_greater_than_42_7()
    {
        return $this->_case_boolean_operator('>', [42, 7], true);
    }

    public function case_operator_greater_than_7_7()
    {
        return $this->_case_boolean_operator('>', [7, 7], false);
    }

    public function case_operator_greater_than_or_equal_to_7_42()
    {
        return $this->_case_boolean_operator('>=', [7, 42], false);
    }

    public function case_operator_greater_than_or_equal_to_42_7()
    {
        return $this->_case_boolean_operator('>=', [42, 7], true);
    }

    public function case_operator_greater_than_or_equal_to_7_7()
    {
        return $this->_case_boolean_operator('>=', [7, 7], true);
    }

    public function case_operator_lower_than_7_42()
    {
        return $this->_case_boolean_operator('<', [7, 42], true);
    }

    public function case_operator_lower_than_42_7()
    {
        return $this->_case_boolean_operator('<', [42, 7], false);
    }

    public function case_operator_lower_than_7_7()
    {
        return $this->_case_boolean_operator('<', [7, 7], false);
    }

    public function case_operator_lower_than_or_equal_to_7_42()
    {
        return $this->_case_boolean_operator('<=', [7, 42], true);
    }

    public function case_operator_lower_than_or_equal_to_42_7()
    {
        return $this->_case_boolean_operator('<=', [42, 7], false);
    }

    public function case_operator_lower_than_or_equal_to_7_7()
    {
        return $this->_case_boolean_operator('<=', [7, 7], true);
    }

    public function case_operator_in_empty_array()
    {
        return $this->_case_boolean_operator('in', [7, []], false);
    }

    public function case_operator_in()
    {
        return $this->_case_boolean_operator('in', [7, [1, 3, 5, 7, 9]], true);
    }

    public function case_operator_in_falsy()
    {
        return $this->_case_boolean_operator('in', [42, [1, 3, 5, 7, 9]], false);
    }

    protected function _case_boolean_operator($operator, array $parameters, $expected)
    {
        $this
            ->given(
                $asserter = new SUT(),
                $operator = $asserter->getOperator($operator)
            )
            ->when($result = call_user_func_array($operator, $parameters))
            ->then
                ->boolean($result)
                    ->isEqualTo($expected);
    }

    public function case_operator_sum_arity_0()
    {
        return $this->_case_operator_sum([], 0);
    }

    public function case_operator_sum_arity_1()
    {
        return $this->_case_operator_sum([7], 7);
    }

    public function case_operator_sum()
    {
        return $this->_case_operator_sum([1, 2, 3, 4, 5, 6, 7, 8, 9], 45);
    }

    protected function _case_operator_sum(array $parameters, $expected)
    {
        $this
            ->given(
                $asserter = new SUT(),
                $operator = $asserter->getOperator('sum')
            )
            ->when($result = call_user_func_array($operator, $parameters))
            ->then
                ->integer($result)
                    ->isEqualTo($expected);
    }

    public function case_operator_matches()
    {
        return $this->_case_boolean_operator('matches', ['foo', '\w+'], true);
    }

    public function case_operator_matches_falsy()
    {
        return $this->_case_boolean_operator('matches', ['foo', '\d+'], false);
    }

    public function case_operator_matches_escaped_delimiter()
    {
        return $this->_case_boolean_operator('matches', ['`foo`', '`\w+`'], true);
    }

    public function case_set_context()
    {
        $this
            ->given(
                $context  = new LUT\Context(),
                $asserter = new SUT()
            )
            ->when($result = $asserter->setContext($context))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_context()
    {
        $this
            ->given(
                $context  = new LUT\Context(),
                $asserter = new SUT(),
                $asserter->setContext($context)
            )
            ->when($result = $asserter->getContext())
            ->then
                ->object($result)
                    ->isIdenticalTo($context);
    }

    public function case_set_operator()
    {
        $this
            ->given(
                $asserter     = new SUT(),
                $oldOperators = $asserter->getOperators(),
                $operator     = function () {}
            )
            ->when($result = $asserter->setOperator('_foo_', $operator))
            ->then
                ->object($result)
                    ->isIdenticalTo($asserter)
                ->integer(count($asserter->getOperators()))
                    ->isEqualTo(count($oldOperators) + 1)
                ->boolean($asserter->operatorExists('_foo_'))
                    ->isTrue()
                ->object($asserter->getOperator('_foo_'))
                    ->isEqualTo(xcallable($operator));
    }

    public function case_set_operator_overwrite()
    {
        $this
            ->given(
                $asserter = new SUT(),
                $asserter->setOperator('_foo_', function () {}),
                $oldOperators = $asserter->getOperators(),
                $operator = function () {}
            )
            ->when($result = $asserter->setOperator('_foo_', $operator))
            ->then
                ->object($result)
                    ->isIdenticalTo($asserter)
                ->integer(count($asserter->getOperators()))
                    ->isEqualTo(count($oldOperators))
                ->boolean($asserter->operatorExists('_foo_'))
                    ->isTrue()
                ->object($asserter->getOperator('_foo_'))
                    ->isEqualTo(xcallable($operator));
    }

    public function case_operator_exists()
    {
        $this
            ->given(
                $asserter = new SUT(),
                $asserter->setOperator('_foo_', function () {})
            )
            ->when($result = $asserter->operatorExists('_foo_'))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_operator_does_not_exist()
    {
        $this
            ->given($asserter = new SUT())
            ->when($result = $asserter->operatorExists('_foo_'))
            ->then
                ->boolean($result)
                    ->isFalse();
    }

    public function case_get_operator()
    {
        $this
            ->given(
                $asserter = new SUT(),
                $operator = function () {},
                $asserter->setOperator('_foo_', $operator)
            )
            ->when($result = $asserter->getOperator('_foo_'))
            ->then
                ->object($result)
                    ->isEqualTo(xcallable($operator));
    }

    public function case_get_undefined_operator()
    {
        $this
            ->given($asserter = new SUT())
            ->when($result = $asserter->getOperator('_foo_'))
            ->then
                ->variable($result)
                    ->isNull();
    }

    public function case_get_operators()
    {
        $this
            ->given($asserter = new SUT())
            ->when($result = $asserter->getOperators())
            ->then
                ->array($result)
                    ->hasSize(14);
    }

    public function case_visit_model()
    {
        $this
            ->given(
                $model             = new LUT\Model(),
                $model->expression = 42,
                $asserter          = new SUT()
            )
            ->when($result = $asserter->visitModel($model))
            ->then
                ->boolean($result)
                    ->isTrue()
                ->boolean($asserter->visit($model))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('sum', [7, 35]),
                $asserter = new SUT()
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_does_not_exist()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('_foo_', [7, 35]),
                $asserter = new SUT()
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Operator _foo_ does not exist.')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Operator _foo_ does not exist.');
    }

    public function case_visit_operator_array_dimension_1()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('x'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return ['x' => $x * 6];
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_array_dimension_2()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('x'),
                $operator->index('y'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return ['x' => ['y' => $x * 6]];
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_array_like_dimension_1()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('x'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return new ArrayObject(['x' => $x * 6]);
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_array_like_dimension_2()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('x'),
                $operator->index('y'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return new ArrayObject(['x' => new ArrayObject(['y' => $x * 6])]);
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_array_dimension_1_undefined_index()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('z'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return ['x' => 42];
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of c()).')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of c()).');
    }

    public function case_visit_operator_array_dimension_1_not_an_array()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('y'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return 42;
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: y (dimension number 1 of c()), because it is not an array.')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: y (dimension number 1 of c()), because it is not an array.');
    }

    public function case_visit_operator_array_like_dimension_1_undefined_index()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->index('z'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return new ArrayObject(['x' => 42]);
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of c()).')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of c()).');
    }

    public function case_visit_operator_attribute_dimension_1()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->attribute('x'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return (object) ['x' => $x * 6];
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_attribute_dimension_2()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->attribute('x'),
                $operator->attribute('y'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return (object) ['x' => (object) ['y' => $x * 6]];
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_attribute_dimension_1_undefined_name()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->attribute('y'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return (object) ['x' => $x * 6];
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: y (dimension number 1 of c()).')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: y (dimension number 1 of c()).');
    }

    public function case_visit_operator_attribute_dimension_1_not_an_object()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c', [7]),
                $operator->attribute('x'),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function ($x) {
                        return $x * 6;
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: x (dimension number 1 of c()), because it is not an object.')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: x (dimension number 1 of c()), because it is not an object.');
    }

    public function case_visit_operator_method_dimension_1()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c'),
                $operator->call(new LUT\Model\Operator('f', [7, 35])),
                $context  = new LUT\Context(['x' => new C()]),
                $asserter = new SUT($context),
                $asserter->setOperator(
                    'c',
                    function () {
                        return new C();
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_method_dimension_2()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c'),
                $operator->call(new LUT\Model\Operator('newMe')),
                $operator->call(new LUT\Model\Operator('f', [7, 35])),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function () {
                        return new C();
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_operator_method_dimension_1_undefined_method()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c'),
                $operator->call(new LUT\Model\Operator('h')),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function () {
                        return new C();
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: h (dimension number 1 of c()).')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: h (dimension number 1 of c()).');
    }

    public function case_visit_operator_method_dimension_1_not_an_object()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c'),
                $operator->call(new LUT\Model\Operator('f', [7, 35])),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function () {
                        return 42;
                    }
                )
            )
            ->exception(function () use ($asserter, $operator) {
                $this->invoke($asserter)->visitOperator($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: f (dimension number 1 of c()), because it is not an object.')
            ->exception(function () use ($asserter, $operator) {
                $asserter->visit($operator);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: f (dimension number 1 of c()), because it is not an object.');
    }

    public function case_visit_operator_mixed_dimensions()
    {
        $this
            ->given(
                $operator = new LUT\Model\Operator('c'),
                $operator->index('x'),
                $operator->attribute('y'),
                $operator->call(new LUT\Model\Operator('f', [7, 35])),
                $asserter = new SUT(),
                $asserter->setOperator(
                    'c',
                    function () {
                        return ['x' => (object) ['y' => new C()]];
                    }
                )
            )
            ->when($result = $this->invoke($asserter)->visitOperator($operator))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($operator))
                    ->isIdenticalTo($result);
    }

    public function case_visit_scalar_null()
    {
        return $this->_case_visit_scalar(null);
    }

    public function case_visit_scalar_boolean()
    {
        return $this->_case_visit_scalar(true);
    }

    public function case_visit_scalar_integer()
    {
        return $this->_case_visit_scalar(7);
    }

    public function case_visit_scalar_float()
    {
        return $this->_case_visit_scalar(4.2);
    }

    public function case_visit_scalar_string()
    {
        return $this->_case_visit_scalar('foo');
    }

    protected function _case_visit_scalar($scalar)
    {
        $this
            ->given(
                $bag      = new LUT\Model\Bag\Scalar($scalar),
                $asserter = new SUT()
            )
            ->when($result = $this->invoke($asserter)->visitScalar($bag))
            ->then
                ->variable($result)
                    ->isIdenticalTo($scalar)
                ->variable($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_array()
    {
        $this
            ->given(
                $bag      = new LUT\Model\Bag\RulerArray(['foo']),
                $asserter = new SUT()
            )
            ->when($result = $this->invoke($asserter)->visitArray($bag))
            ->then
                ->array($result)
                    ->isEqualTo(['foo'])
                ->variable($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_undefined_context()
    {
        $this
            ->given(
                $bag      = new LUT\Model\Bag\Context('x'),
                $asserter = new SUT()
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Assert needs a context to work properly.')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Assert needs a context to work properly.');
    }

    public function case_visit_context_undefined_reference()
    {
        $this
            ->given(
                $bag      = new LUT\Model\Bag\Context('x'),
                $context  = new LUT\Context(),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Context reference x does not exist.')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Context reference x does not exist.');
    }

    public function case_visit_context()
    {
        $this
            ->given(
                $bag      = new LUT\Model\Bag\Context('x'),
                $context  = new LUT\Context(['x' => 42]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_array_dimension_1()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->index('y'),
                $context  = new LUT\Context(['x' => ['y' => 42]]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_array_dimension_2()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->index('y'),
                $bag->index('z'),
                $context  = new LUT\Context(['x' => ['y' => ['z' => 42]]]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_array_dimension_1_undefined_index()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->index('z'),
                $context  = new LUT\Context(['x' => ['y' => 42]]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of x).')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: z (dimension number 1 of x).');
    }

    public function case_visit_context_array_dimension_1_not_an_array()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->index('y'),
                $context  = new LUT\Context(['x' => 42]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: y (dimension number 1 of x), because it is not an array.')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to access to an undefined index: y (dimension number 1 of x), because it is not an array.');
    }

    public function case_visit_context_attribute_dimension_1()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->attribute('y'),
                $context  = new LUT\Context(['x' => (object) ['y' => 42]]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_attribute_dimension_2()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->attribute('y'),
                $bag->attribute('z'),
                $context  = new LUT\Context(['x' => (object) ['y' => (object) ['z' => 42]]]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_attribute_dimension_1_undefined_name()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->attribute('z'),
                $context  = new LUT\Context(['x' => (object) ['y' => 42]]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: z (dimension number 1 of x).')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: z (dimension number 1 of x).');
    }

    public function case_visit_context_attribute_dimension_1_not_an_object()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->attribute('y'),
                $context  = new LUT\Context(['x' => 42]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: y (dimension number 1 of x), because it is not an object.')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to read an undefined attribute: y (dimension number 1 of x), because it is not an object.');
    }

    public function case_visit_context_method_dimension_1()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->call(new LUT\Model\Operator('f', [7, 35])),
                $context  = new LUT\Context(['x' => new C()]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_method_dimension_2()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->call(new LUT\Model\Operator('newMe')),
                $bag->call(new LUT\Model\Operator('f', [7, 35])),
                $context  = new LUT\Context(['x' => new C()]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }

    public function case_visit_context_method_dimension_1_undefined_method()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->call(new LUT\Model\Operator('h')),
                $context  = new LUT\Context(['x' => new C()]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: h (dimension number 1 of x).')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: h (dimension number 1 of x).');
    }

    public function case_visit_context_method_dimension_1_not_an_object()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->call(new LUT\Model\Operator('f', [7, 35])),
                $context  = new LUT\Context(['x' => 42]),
                $asserter = new SUT($context)
            )
            ->exception(function () use ($asserter, $bag) {
                $this->invoke($asserter)->visitContext($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: f (dimension number 1 of x), because it is not an object.')
            ->exception(function () use ($asserter, $bag) {
                $asserter->visit($bag);
            })
                ->isInstanceOf(LUT\Exception\Asserter::class)
                ->hasMessage('Try to call an undefined method: f (dimension number 1 of x), because it is not an object.');
    }

    public function case_visit_context_mixed_dimensions()
    {
        $this
            ->given(
                $bag = new LUT\Model\Bag\Context('x'),
                $bag->index('y'),
                $bag->attribute('z'),
                $bag->call(new LUT\Model\Operator('f', [7, 35])),
                $context  = new LUT\Context(['x' => ['y' => (object) ['z' => new C()]]]),
                $asserter = new SUT($context)
            )
            ->when($result = $this->invoke($asserter)->visitContext($bag))
            ->then
                ->integer($result)
                    ->isEqualTo(42)
                ->integer($asserter->visit($bag))
                    ->isIdenticalTo($result);
    }
}

class C
{
    public function f($x, $y)
    {
        return $x + $y;
    }

    public function newMe()
    {
        return new self();
    }
}
