<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Ivan Enderlin. All rights reserved.
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

use Hoa\Test;
use Hoa\Ruler as LUT;

/**
 * Class \Hoa\Ruler\Test\Unit\Model\Asserter.
 *
 * Test suite of the asserter object of the visitor.
 *
 * @author     Alexis von Glasow <alexis.von-glasow@hoa-project.net>
 * @copyright  Copyright © 2007-2015 Alexis von Glasow.
 * @license    New BSD License
 */

class Asserter extends Test\Unit\Suite {

    public function case_keep_errors ( ) {
        $this
            ->given(
                $ruler     = new LUT(),
                $fExecuted = false,
                $asserter  = $ruler->getDefaultAsserter(),
                $asserter->setOperator(
                    'f',
                    function ( $a = false ) use ( &$fExecuted ) {

                        $fExecuted = true;

                        return $a;
                    }
                ),
                $rule = 'f(false)'
            )
            ->when($result = $ruler->assert($rule, new LUT\Context()))
            ->then
                ->boolean($result)
                    ->isFalse()
                ->boolean($fExecuted)
                    ->isTrue()
                ->boolean($asserter->hasError())
                    ->isTrue()
                ->array($asserter->getErrors())
                    ->isNotEmpty()
                    ->hasKey('f')
            ;
    }
}

