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

namespace Hoa\Ruler;

/**
 * Class \Hoa\Ruler\Context.
 *
 * Context of a rule, it contains data to instanciate the rule.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Context implements \ArrayAccess
{
    /**
     * Ruler.
     *
     * @var \Hoa\Ruler
     */
    protected $_ruler = null;

    /**
     * Data.
     *
     * @var array
     */
    protected $_data  = [];



    /**
     * Constructor.
     *
     * @param   array  $data    Initial data.
     */
    public function __construct(array $data = [])
    {
        $this->_data = $data;

        return;
    }

    /**
     * Set a data.
     *
     * @param   string  $id       ID.
     * @param   mixed   $value    Value.
     * @return  void
     */
    public function offsetSet($id, $value)
    {
        $this->_data[$id] = $value;

        return;
    }

    /**
     * Get a data.
     *
     * @param   string  $id    ID.
     * @return  mixed
     * @throws  \Hoa\Ruler\Exception
     */
    public function offsetGet($id)
    {
        if (false === array_key_exists($id, $this->_data)) {
            throw new Exception(
                'Identifier %s does not exist in the context.',
                0,
                $id
            );
        }

        $value = $this->_data[$id];

        if ($value instanceof DynamicCallable) {
            return $value($this);
        }

        if (true === is_callable($value)) {
            if (true  === is_string($value) &&
                false === in_array(strtolower($value), get_defined_functions()['user'])) {
                return $value;
            }

            $value = $this->_data[$id] = $value($this);
        }

        return $value;
    }

    /**
     * Check if a data exists.
     *
     * @return  bool
     */
    public function offsetExists($id)
    {
        return true === array_key_exists($id, $this->_data);
    }

    /**
     * Unset a data.
     *
     * @param   string  $id    ID.
     * @return  void
     */
    public function offsetUnset($id)
    {
        unset($this->_data[$id]);

        return;
    }
}
