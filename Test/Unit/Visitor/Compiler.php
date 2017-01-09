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
use Hoa\Ruler\Visitor\Compiler as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Visitor\Compiler.
 *
 * Test suite of the compiler visitor.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Compiler extends Test\Unit\Suite
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
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    true;'
        );
    }

    public function case_operator()
    {
        return $this->_case(
            '7 < 42',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->{\'<\'}(' . "\n" .
            '        7,' . "\n" .
            '        42' . "\n" .
            '    );'
        );
    }

    public function case_operator_is_an_identifier()
    {
        return $this->_case(
            'true and false',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->and(' . "\n" .
            '        true,' . "\n" .
            '        false' . "\n" .
            '    );'
        );
    }

    public function case_function()
    {
        return $this->_case(
            'f(7, 42)',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'f\',' . "\n" .
            '        7,' . "\n" .
            '        42' . "\n" .
            '    );'
        );
    }

    public function case_function_of_arity_1()
    {
        return $this->_case(
            'f(7)',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'f\',' . "\n" .
            '        7' . "\n" .
            '    );'
        );
    }

    public function case_function_with_array_dimension()
    {
        return $this->_case(
            'x(7)[42]',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'x\',' . "\n" .
            '        7' . "\n" .
            '    )' . "\n" .
            '        ->index(' . "\n" .
            '            42' . "\n" .
            '        );'
        );
    }

    public function case_function_with_attribute_dimension()
    {
        return $this->_case(
            'x(7).y',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'x\',' . "\n" .
            '        7' . "\n" .
            '    )' . "\n" .
            '        ->attribute(\'y\');'
        );
    }

    public function case_function_with_call_dimension()
    {
        return $this->_case(
            'x(7).y(42)',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'x\',' . "\n" .
            '        7' . "\n" .
            '    )' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'y\',' . "\n" .
            '                42' . "\n" .
            '            )' . "\n" .
            '        );'
        );
    }

    public function case_function_with_many_dimensions()
    {
        return $this->_case(
            'x(7).y(42).z[153]',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->func(' . "\n" .
            '        \'x\',' . "\n" .
            '        7' . "\n" .
            '    )' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'y\',' . "\n" .
            '                42' . "\n" .
            '            )' . "\n" .
            '        )' . "\n" .
            '        ->attribute(\'z\')' . "\n" .
            '        ->index(' . "\n" .
            '            153' . "\n" .
            '        );'
        );
    }

    public function case_scalar_true()
    {
        return $this->_case(
            'true',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    true;'
        );
    }

    public function case_scalar_false()
    {
        return $this->_case(
            'false',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    false;'
        );
    }

    public function case_scalar_null()
    {
        return $this->_case(
            'null and true',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->and(' . "\n" .
            '        null,' . "\n" .
            '        true' . "\n" .
            '    );'
        );
    }

    public function case_scalar_numeric()
    {
        return $this->_case(
            '7',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    7;'
        );
    }

    public function case_scalar_string()
    {
        return $this->_case(
            "'Hello, World!'",
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    \'Hello, World!\';'
        );
    }

    public function case_scalar_escaped_string()
    {
        return $this->_case(
            "'He\llo, \'World\'!'",
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    \'He\llo, \\\'World\\\'!\';'
        );
    }

    public function case_array()
    {
        return $this->_case(
            '[7, true, \'foo\']',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    [' . "\n" .
            '        7,' . "\n" .
            '        true,' . "\n" .
            '        \'foo\'' . "\n" .
            '    ];'
        );
    }

    public function case_context()
    {
        return $this->_case(
            'x',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\');'
        );
    }

    public function case_context_with_array_dimension()
    {
        return $this->_case(
            'x[7]',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->index(' . "\n" .
            '            7' . "\n" .
            '        );'
        );
    }

    public function case_context_with_array_dimensions()
    {
        return $this->_case(
            'x[7][42]',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->index(' . "\n" .
            '            7' . "\n" .
            '        )' . "\n" .
            '        ->index(' . "\n" .
            '            42' . "\n" .
            '        );'
        );
    }

    public function case_context_with_attribute_dimension()
    {
        return $this->_case(
            'x.y',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->attribute(\'y\');'
        );
    }

    public function case_context_with_attribute_dimensions()
    {
        return $this->_case(
            'x.y.z',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->attribute(\'y\')' . "\n" .
            '        ->attribute(\'z\');'
        );
    }

    public function case_context_with_call_dimension()
    {
        return $this->_case(
            'x.y(7)',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'y\',' . "\n" .
            '                7' . "\n" .
            '            )' . "\n" .
            '        );'
        );
    }

    public function case_context_with_call_dimensions()
    {
        return $this->_case(
            'x.y(7).z(42)',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'x\')' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'y\',' . "\n" .
            '                7' . "\n" .
            '            )' . "\n" .
            '        )' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'z\',' . "\n" .
            '                42' . "\n" .
            '            )' . "\n" .
            '        );'
        );
    }

    public function case_context_with_many_dimensions()
    {
        return $this->_case(
            'a.b(7).c[42].d.e(153).f',
            '$model = new \Hoa\Ruler\Model();' . "\n" .
            '$model->expression =' . "\n" .
            '    $model->variable(\'a\')' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'b\',' . "\n" .
            '                7' . "\n" .
            '            )' . "\n" .
            '        )' . "\n" .
            '        ->attribute(\'c\')' . "\n" .
            '        ->index(' . "\n" .
            '            42' . "\n" .
            '        )' . "\n" .
            '        ->attribute(\'d\')' . "\n" .
            '        ->call(' . "\n" .
            '            $model->func(' . "\n" .
            '                \'e\',' . "\n" .
            '                153' . "\n" .
            '            )' . "\n" .
            '        )' . "\n" .
            '        ->attribute(\'f\');'
        );
    }

    protected function _case($rule, $compiled)
    {
        $this
            ->given($compiler = new SUT())
            ->when($result = $compiler->visit(LUT::interpret($rule)))
            ->then
                ->string($result)
                    ->isEqualTo($compiled);
    }
}
