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
use Hoa\Test;

/**
 * Class \Hoa\Ruler\Test\Unit\Issue.
 *
 * Test suite of detected issues.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Issue extends Test\Unit\Suite implements Test\Decorrelated
{
    public function case_github_50()
    {
        $this
            ->given(
                $ruler               = new LUT(),
                $rule                = 'variable',
                $context             = new LUT\Context(),
                $context['variable'] = 'file'
            )
            ->when(function () use ($ruler, $rule, $context) {
                $ruler->assert($rule, $context);
            })
                ->error()
                    ->notExists();
    }

    public function case_github_70()
    {
        $this
            ->given(
                $ruler               = new LUT(),
                $rule                = 'variable["foo"] is null',
                $context             = new LUT\Context(),
                $context['variable'] = ['foo' => null]
            )
            ->when($result = $ruler->assert($rule, $context))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_github_100_1()
    {
        $this
            ->given(
                $ruler = new LUT(),
                $rule  = '(false and true) or true'
            )
            ->when($result = $ruler->assert($rule))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_github_100_2()
    {
        $this
            ->given(
                $ruler = new LUT(),
                $rule  = 'false and true or true'
            )
            ->when($result = $ruler->assert($rule))
            ->then
                ->boolean($result)
                    ->isTrue();
    }

    public function case_github_100_3()
    {
        $this
            ->given(
                $ruler = new LUT(),
                $rule  = 'true or true and false'
            )
            ->when($result = $ruler->assert($rule))
            ->then
                ->boolean($result)
                    ->isTrue();
    }
}
