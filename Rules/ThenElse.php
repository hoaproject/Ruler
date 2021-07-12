<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2016, Hoa community. All rights reserved.
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

namespace Hoa\Ruler\Rules;

use Hoa\Ruler\Context;
use Hoa\Ruler\Exception\RuleDoesNotValidate;
use Hoa\Ruler\Rule;
use Hoa\Ruler\Ruler;

/**
 * Class \Hoa\Ruler\Rules\ThenElse.
 *
 * A rule returning a value when the assertion passes and another value when
 * the assertion does not pass.
 *
 * @copyright  Copyright © 2007-2016 Hoa community
 * @license    New BSD License
 */
class ThenElse implements Rule
{
    /**
     * @var Then
     */
    private $rule;

    /**
     * @var mixed
     */
    private $otherwise;

    /**
     * @param string $rule
     * @param mixed  $result
     * @param mixed  $otherwise
     */
    public function __construct($rule, $result, $otherwise)
    {
        $this->rule      = new Then($rule, $result);
        $this->otherwise = $otherwise;
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return bool
     */
    public function valid(Ruler $ruler, Context $context)
    {
        return $this->rule->valid($ruler, $context);
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return mixed
     */
    public function execute(Ruler $ruler, Context $context)
    {
        try {
            return $this->rule->execute($ruler, $context);
        } catch (RuleDoesNotValidate $e) {
            return $this->otherwise;
        }
    }
}
