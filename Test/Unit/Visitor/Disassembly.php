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
use Hoa\Ruler\Visitor\Disassembly as SUT;
use Hoa\Test;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Test\Unit\Visitor\Disassembly.
 *
 * Test suite of the disassembly visitor.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Disassembly extends Test\Unit\Suite
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
            'true'
        );
    }

    public function case_operator()
    {
        return $this->_case(
            '7 < 42',
            '(7 < 42)'
        );
    }

    public function case_operator_is_an_identifier()
    {
        return $this->_case(
            'true and false',
            '(true and false)'
        );
    }

    public function case_function()
    {
        return $this->_case(
            'f(7, 42)',
            'f(7, 42)'
        );
    }

    public function case_function_of_arity_1()
    {
        return $this->_case(
            'f(7)',
            'f(7)'
        );
    }

    public function case_function_with_array_dimensions()
    {
        return $this->_case(
            'x(7)[42]',
            'x(7)[42]'
        );
    }

    public function case_function_with_attribute_dimensions()
    {
        return $this->_case(
            'x(7).y',
            'x(7).y'
        );
    }

    public function case_function_with_call_dimensions()
    {
        return $this->_case(
            'x(7).y(42)',
            'x(7).y(42)'
        );
    }

    public function case_function_with_many_dimensions()
    {
        return $this->_case(
            'x(7).y(42).z[153]',
            'x(7).y(42).z[153]'
        );
    }

    public function case_scalar_true()
    {
        return $this->_case(
            'true',
            'true'
        );
    }

    public function case_scalar_false()
    {
        return $this->_case(
            'false',
            'false'
        );
    }

    public function case_scalar_null()
    {
        return $this->_case(
            'null and true',
            '(null and true)'
        );
    }

    public function case_scalar_numeric()
    {
        return $this->_case(
            '7',
            '7'
        );
    }

    public function case_scalar_string()
    {
        return $this->_case(
            "'Hello, World!'",
            "'Hello, World!'"
        );
    }

    public function case_scalar_escaped_string()
    {
        return $this->_case(
            "'He\llo, \'World\'!'",
            "'He\llo, \'World\'!'"
        );
    }

    public function case_array()
    {
        return $this->_case(
            '[7, true, \'foo\']',
            '[7, true, \'foo\']'
        );
    }

    public function case_context()
    {
        return $this->_case(
            'x',
            'x'
        );
    }

    public function case_context_with_array_dimension()
    {
        return $this->_case(
            'x[7]',
            'x[7]'
        );
    }

    public function case_context_with_attribute_dimension()
    {
        return $this->_case(
            'x.y',
            'x.y'
        );
    }

    public function case_context_with_call_dimension()
    {
        return $this->_case(
            'x.y(7)',
            'x.y(7)'
        );
    }

    public function case_context_with_many_dimensions()
    {
        return $this->_case(
            'x.y(7).z[42]',
            'x.y(7).z[42]'
        );
    }

    protected function _case($rule, $disassembled)
    {
        $this
            ->given($compiler = new SUT())
            ->when($result = $compiler->visit(LUT::interpret($rule)))
            ->then
                ->string($result)
                    ->isEqualTo($disassembled);
    }
}
