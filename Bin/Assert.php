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

namespace Hoa\Ruler\Bin;

use Hoa\Console;
use Hoa\Ruler;

/**
 * Class \Hoa\Ruler\Bin\Assert.
 *
 * Assert rules.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Assert extends Console\Dispatcher\Kit
{
    /**
     * Options description.
     *
     * @var array
     */
    protected $options = [
        ['help', Console\GetOption::NO_ARGUMENT, 'h'],
        ['help', Console\GetOption::NO_ARGUMENT, '?']
    ];



    /**
     * The entry method.
     *
     * @return  int
     */
    public function main()
    {
        $ruler   = new Ruler();
        $context = new Ruler\Context();

        while (false !== $c = $this->getOption($v)) {
            switch ($c) {
                case '__ambiguous':
                    $context[$v['option']] = $v['value'];

                    break;

                case 'h':
                case '?':
                    return $this->usage();
            }
        }

        $this->parser->listInputs($rule);

        if (empty($rule)) {
            return $this->usage();
        }

        return (int) (!$ruler->assert($rule, $context));
    }

    /**
     * The command usage.
     *
     * @return  int
     */
    public function usage()
    {
        echo
            'Usage   : ruler:assert <options> rule', "\n",
            'Options :', "\n",
            $this->makeUsageOptionsList([
                'help' => 'This help.'
            ]), "\n",
            'Example : -x=2 -y=6 \'x in [1, 2, 4] and x < y\'.', "\n",
            'See $? to see the result (0 for true, > 0 for false).', "\n";

        return;
    }
}

__halt_compiler();
Assert rules.
