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

namespace Hoa\Ruler;

use Hoa\Ruler\Exception\NoResult;
use Hoa\Ruler\Exception\RuleDoesNotValidate;

/**
 * Class \Hoa\Ruler\Rules.
 *
 * A collection of rules ordered by priority.
 *
 * @copyright  Copyright © 2007-2016 Hoa community
 * @license    New BSD License
 */
class Rules
{
    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    public function __construct()
    {
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * @param string $name
     * @param Rule   $rule
     * @param int    $priority
     *
     * @return Rules
     */
    public function add($name, Rule $rule, $priority = -1)
    {
        $rules = clone $this;

        $rules->queue->insert([$name, $rule], $priority);

        return $rules;
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @throws NoResult
     *
     * @return Result
     */
    public function getBestResult(Ruler $ruler, Context $context)
    {
        $ruler = self::initializeRuler($ruler, $context);
        $queue = clone $this->queue;

        foreach ($queue as $nameAndRule) {
            /**
             * @var string $name
             * @var Rule   $rule
             */
            list($name, $rule) = $nameAndRule;

            try {
                $context['#' . $name] = $rule->execute($ruler, $context);
            } catch (RuleDoesNotValidate $exception) {
                $context['#' . $name] = null;
            }

            if ($rule->valid($ruler, $context)) {
                return new Result($name, $rule, $context['#' . $name]);
            }
        }

        throw new NoResult();
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return Result[]
     */
    public function getAllResults(Ruler $ruler, Context $context)
    {
        $ruler   = self::initializeRuler($ruler, $context);
        $queue   = clone $this->queue;
        $results = [];

        foreach ($queue as $nameAndRule) {
            /**
             * @var string $name
             * @var Rule   $rule
             */
            list($name, $rule) = $nameAndRule;

            try {
                $context['#' . $name] = $rule->execute($ruler, $context);
            } catch (RuleDoesNotValidate $exception) {
                $context['#' . $name] = null;
            }

            $results[] = new Result($name, $rule, $context['#' . $name]);
        }

        return $results;
    }

    /**
     * @param Ruler $ruler
     * @param array $context
     *
     * @return Ruler
     */
    private static function initializeRuler(Ruler $ruler, Context $context)
    {
        if ($ruler->getDefaultAsserter()->operatorExists('rule')) {
            return $ruler;
        }

        $ruler = clone $ruler;

        $ruler->getDefaultAsserter()->setOperator('rule', function ($id) use ($context) {
            return $context['#' . $id];
        });

        return $ruler;
    }
}
