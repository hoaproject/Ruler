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

use Hoa\Ruler as LUT;
use Hoa\Ruler\Visitor\Interpreter as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Visitor\Interpreter.
 *
 * Test suite of the interpreter visitor.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Interpreter extends Test\Unit\Suite
{
    public function case_is_a_visitor()
    {
        $this
            ->when($result = new SUT())
            ->then
                ->object($result)
                    ->isInstanceOf(Visitor\Visit::class);
    }

    public function case_model()
    {
        return $this->_case(
            'true',
            function () {
                $model = new LUT\Model();
                $model->expression = true;

                return $model;
            }
        );
    }

    public function case_operator()
    {
        return $this->_case(
            '7 < 42',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->{'<'}(
                        7,
                        42
                    );

                return $model;
            }
        );
    }

    public function case_operator_is_an_identifier()
    {
        return $this->_case(
            'true and false',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->and(
                        true,
                        false
                    );

                return $model;
            }
        );
    }

    public function case_operator_and()
    {
        return $this->_case(
            'true and false',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->and(
                        true,
                        false
                    );

                return $model;
            }
        );
    }

    public function case_operator_or()
    {
        return $this->_case(
            'true or false',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->or(
                        true,
                        false
                    );

                return $model;
            }
        );
    }

    public function case_operator_xor()
    {
        return $this->_case(
            'true xor false',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->xor(
                        true,
                        false
                    );

                return $model;
            }
        );
    }

    public function case_operator_not()
    {
        return $this->_case(
            'not true',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->not(
                        true
                    );

                return $model;
            }
        );
    }

    public function case_function()
    {
        return $this->_case(
            'f(7, 42)',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'f',
                        7,
                        42
                    );

                return $model;
            }
        );
    }

    public function case_function_of_arity_1()
    {
        return $this->_case(
            'f(7)',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'f',
                        7
                    );

                return $model;
            }
        );
    }

    public function case_function_with_array_dimension()
    {
        return $this->_case(
            'x(7)[42]',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'x',
                        7
                    )
                        ->index(
                            42
                        );

                return $model;
            }
        );
    }

    public function case_function_with_attribute_dimension()
    {
        return $this->_case(
            'x(7).y',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'x',
                        7
                    )
                        ->attribute('y');

                return $model;
            }
        );
    }

    public function case_function_with_call_dimension()
    {
        return $this->_case(
            'x(7).y(42)',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'x',
                        7
                    )
                        ->call(
                            $model->func(
                                'y',
                                42
                            )
                        );

                return $model;
            }
        );
    }

    public function case_function_with_many_dimensions()
    {
        return $this->_case(
            'x(7).y(42).z[153]',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->func(
                        'x',
                        7
                    )
                        ->call(
                            $model->func(
                                'y',
                                42
                            )
                        )
                        ->attribute('z')
                        ->index(
                            153
                        );

                return $model;
            }
        );
    }

    public function case_scalar_true()
    {
        return $this->_case(
            'true',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    true;

                return $model;
            }
        );
    }

    public function case_scalar_false()
    {
        return $this->_case(
            'false',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    false;

                return $model;
            }
        );
    }

    public function case_scalar_null()
    {
        return $this->_case(
            'null and true',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->and(
                        null,
                        true
                    );

                return $model;
            }
        );
    }

    public function case_scalar_float()
    {
        return $this->_case(
            '4.2',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    4.2;

                return $model;
            }
        );
    }

    public function case_scalar_integer()
    {
        return $this->_case(
            '7',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    7;

                return $model;
            }
        );
    }

    public function case_scalar_string()
    {
        return $this->_case(
            "'Hello, World!'",
            function () {
                $model = new LUT\Model();
                $model->expression =
                    'Hello, World!';

                return $model;
            }
        );
    }

    public function case_scalar_escaped_string()
    {
        return $this->_case(
            "'He\llo, \'World\'!'",
            function () {
                $model = new LUT\Model();
                $model->expression =
                    'He\llo, \'World\'!';

                return $model;
            }
        );
    }

    public function case_array()
    {
        return $this->_case(
            '[7, true, \'foo\']',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    [
                        7,
                        true,
                        'foo'
                    ];

                return $model;
            }
        );
    }

    public function case_context()
    {
        return $this->_case(
            'x',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('x');

                return $model;
            }
        );
    }

    public function case_context_with_array_dimension()
    {
        return $this->_case(
            'x[7]',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('x')
                        ->index(
                            7
                        );

                return $model;
            }
        );
    }

    public function case_context_with_array_dimensions()
    {
        return $this->_case(
            'x[7][42]',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('x')
                        ->index(
                            7
                        )
                        ->index(
                            42
                        );

                return $model;
            }
        );
    }

    public function case_context_with_attribute_dimension()
    {
        return $this->_case(
            'x.y',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('x')
                        ->attribute('y');

                return $model;
            }
        );
    }

    public function case_context_with_attribute_dimensions()
    {
        return $this->_case(
            'x.y.z',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('x')
                        ->attribute('y')
                        ->attribute('z');

                return $model;
            }
        );
    }

    public function case_context_with_many_dimensions()
    {
        return $this->_case(
            'a.b(7).c[42].d.e(153).f',
            function () {
                $model = new LUT\Model();
                $model->expression =
                    $model->variable('a')
                        ->call(
                            $model->func(
                                'b',
                                7
                            )
                        )
                        ->attribute('c')
                        ->index(
                            42
                        )
                        ->attribute('d')
                        ->call(
                            $model->func(
                                'e',
                                153
                            )
                        )
                        ->attribute('f');

                return $model;
            }
        );
    }

    protected function _case($rule, \Closure $expected)
    {
        $this
            ->given(
                $interpreter = new SUT(),
                $ast         = LUT::getCompiler()->parse($rule)
            )
            ->when($result = $interpreter->visit($ast))
            ->then
                ->object($result)
                    ->isEqualTo($expected());
    }
}
