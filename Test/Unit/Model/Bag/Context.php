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
 * CONSE DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTER HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISIY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIF SUCH DAMAGE.
 */

namespace Hoa\Ruler\Test\Unit\Model\Bag;

use Hoa\Ruler as LUT;
use Hoa\Ruler\Model\Bag\Context as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Ruler\Test\Unit\Model\Bag\Context.
 *
 * Test suite of the context bag class.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context extends Test\Unit\Suite
{
    public function case_is_a_bag()
    {
        $this
            ->when($result = new SUT('foobar'))
            ->then
                ->object($result)
                    ->isInstanceOf(LUT\Model\Bag::class);
    }

    public function case_constructor()
    {
        $this
            ->given($id = 'foobar')
            ->when($result = new SUT($id))
            ->then
                ->string($result->getId())
                    ->isEqualTo($id)
                ->array($result->getDimensions())
                    ->isEmpty();
    }

    public function case_scalar_index_from_root()
    {
        return $this->_case_index_from_root(
            'baz',
            new LUT\Model\Bag\Scalar('baz')
        );
    }

    public function case_array_index_from_root()
    {
        return $this->_case_index_from_root(
            ['baz'],
            new LUT\Model\Bag\RulerArray(['baz'])
        );
    }

    public function case_bag_index_from_root()
    {
        return $this->_case_index_from_root(
            new LUT\Model\Bag\Scalar('baz'),
            new LUT\Model\Bag\Scalar('baz')
        );
    }

    protected function _case_index_from_root($index, $expectedIndex)
    {
        $this
            ->given(
                $id      = 'foobar',
                $context = new SUT($id)
            )
            ->when($result = $context->index($index))
            ->then
                ->object($result)
                    ->isIdenticalTo($context)
                ->array($result->getDimensions())
                    ->isEqualTo([
                        [
                            SUT::ACCESS_TYPE  => SUT::ARRAY_ACCESS,
                            SUT::ACCESS_VALUE => $expectedIndex
                        ]
                    ]);
    }

    public function case_scalar_index()
    {
        return $this->_case_index(
            'baz',
            new LUT\Model\Bag\Scalar('baz')
        );
    }

    public function case_array_index()
    {
        return $this->_case_index(
            ['baz'],
            new LUT\Model\Bag\RulerArray(['baz'])
        );
    }

    public function case_bag_index()
    {
        return $this->_case_index(
            new LUT\Model\Bag\Scalar('baz'),
            new LUT\Model\Bag\Scalar('baz')
        );
    }

    protected function _case_index($index, $expectedIndex)
    {
        $this
            ->given(
                $id      = 'foobar',
                $context = new SUT($id),
                $context->index(new LUT\Model\Bag\Scalar('qux'))
            )
            ->when($result = $context->index($index))
            ->then
                ->object($result)
                    ->isIdenticalTo($context)
                ->array($result->getDimensions())
                    ->isEqualTo([
                        [
                            SUT::ACCESS_TYPE  => SUT::ARRAY_ACCESS,
                            SUT::ACCESS_VALUE => new LUT\Model\Bag\Scalar('qux')
                        ],
                        [
                            SUT::ACCESS_TYPE  => SUT::ARRAY_ACCESS,
                            SUT::ACCESS_VALUE => $expectedIndex
                        ]
                    ]);
    }

    public function case_attribute()
    {
        $this
            ->given(
                $id        = 'foobar',
                $attribute = 'bazqux',
                $context   = new SUT($id)
            )
            ->when($result = $context->attribute($attribute))
            ->then
                ->object($result)
                    ->isIdenticalTo($context)
                ->array($result->getDimensions())
                    ->isEqualTo([
                        [
                            SUT::ACCESS_TYPE  => SUT::ATTRIBUTE_ACCESS,
                            SUT::ACCESS_VALUE => $attribute
                        ]
                    ]);
    }

    public function case_call()
    {
        $this
            ->given(
                $id      = 'foobar',
                $method  = new LUT\Model\Operator('f'),
                $context = new SUT($id)
            )
            ->when($result = $context->call($method))
            ->then
                ->object($result)
                    ->isIdenticalTo($context)
                ->array($result->getDimensions())
                    ->isEqualTo([
                        [
                            SUT::ACCESS_TYPE  => SUT::METHOD_ACCESS,
                            SUT::ACCESS_VALUE => $method
                        ]
                    ]);
    }

    public function case_multiple_dimension_types()
    {
        $this
            ->given(
                $id        = 'foobar',
                $index     = 'baz',
                $attribute = 'qux',
                $method    = new LUT\Model\Operator('f'),
                $context   = new SUT($id),
                $context->attribute($attribute),
                $context->index($index),
                $context->call($method)
            )
            ->when($result = $context->getDimensions())
            ->then
                ->array($result)
                    ->isEqualTo([
                        [
                            SUT::ACCESS_TYPE  => SUT::ATTRIBUTE_ACCESS,
                            SUT::ACCESS_VALUE => $attribute
                        ],
                        [
                            SUT::ACCESS_TYPE  => SUT::ARRAY_ACCESS,
                            SUT::ACCESS_VALUE => new LUT\Model\Bag\Scalar($index)
                        ],
                        [
                            SUT::ACCESS_TYPE  => SUT::METHOD_ACCESS,
                            SUT::ACCESS_VALUE => $method
                        ]
                    ]);
    }
}
