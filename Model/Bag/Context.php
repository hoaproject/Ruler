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

namespace Hoa\Ruler\Model\Bag;

use Hoa\Ruler;

/**
 * Class \Hoa\Ruler\Model\Bag\Context.
 *
 * Bag for context, i.e. a variable.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context extends Bag
{
    /**
     * Access type.
     *
     * @const int
     */
    const ACCESS_TYPE      = 0;

    /**
     * Access value.
     *
     * @const int
     */
    const ACCESS_VALUE     = 1;

    /**
     * Type: array access.
     *
     * @const int
     */
    const ARRAY_ACCESS     = 0;

    /**
     * Type: attribute access.
     *
     * @const int
     */
    const ATTRIBUTE_ACCESS = 1;

    /**
     * Type: method access.
     *
     * @const int
     */
    const METHOD_ACCESS    = 2;

    /**
     * ID.
     *
     * @var string
     */
    protected $_id         = null;

    /**
     * Index and object accesses.
     *
     * @var array
     */
    protected $_dimensions = [];



    /**
     * Constructor.
     *
     * @param   string  $id    ID.
     */
    public function __construct($id)
    {
        $this->_id = $id;

        return;
    }

    /**
     * Call an index (variable[indexA][indexB][indexC]).
     *
     * @param   mixed  $index    Index (a bag, a scalar or an array).
     * @return  \Hoa\Ruler\Model\Bag\Context
     */
    public function index($index)
    {
        if (is_scalar($index) || null === $index) {
            $index = new Scalar($index);
        } elseif (is_array($index)) {
            $index = new RulerArray($index);
        }

        $this->_dimensions[] = [
            static::ACCESS_TYPE  => static::ARRAY_ACCESS,
            static::ACCESS_VALUE => $index
        ];

        return $this;
    }

    /**
     * Call an attribute (variable.attrA.attrB).
     *
     * @param   string  $attribute    Attribute name.
     * @return  \Hoa\Ruler\Model\Bag\Context
     */
    public function attribute($attribute)
    {
        $this->_dimensions[] = [
            static::ACCESS_TYPE  => static::ATTRIBUTE_ACCESS,
            static::ACCESS_VALUE => $attribute
        ];

        return $this;
    }

    /**
     * Call a method (variable.foo().bar().baz()).
     *
     * @param   \Hoa\Ruler\Model\Operator  $method    Method to call.
     * @return  \Hoa\Ruler\Model\Bag\Context
     */
    public function call(Ruler\Model\Operator $method)
    {
        $this->_dimensions[] = [
            static::ACCESS_TYPE  => static::METHOD_ACCESS,
            static::ACCESS_VALUE => $method
        ];

        return $this;
    }

    /**
     * Get all dimensions.
     *
     * @return  array
     */
    public function getDimensions()
    {
        return $this->_dimensions;
    }

    /**
     * Get ID.
     *
     * @return  string
     */
    public function getId()
    {
        return $this->_id;
    }
}
