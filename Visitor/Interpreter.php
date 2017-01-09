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

namespace Hoa\Ruler\Visitor;

use Hoa\Ruler;
use Hoa\Visitor;

/**
 * Class \Hoa\Ruler\Visitor\Interpreter.
 *
 * Interpreter: rule to model.
 *
 * @copyright  Copyright © 2007-2017 Hoa community
 * @license    New BSD License
 */
class Interpreter implements Visitor\Visit
{
    /**
     * Root.
     *
     * @var \Hoa\Ruler\Model
     */
    protected $_root    = null;

    /**
     * Current node.
     *
     * @var \Hoa\Ruler\Visitor\Interpreter
     */
    protected $_current = null;



    /**
     * Visit an element.
     *
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     * @throws  \Hoa\Ruler\Exception\Interpreter
     */
    public function visit(Visitor\Element $element, &$handle = null, $eldnah = null)
    {
        $id       = $element->getId();
        $variable = false !== $eldnah;

        switch ($id) {
            case '#expression':
                $this->_root             = new Ruler\Model();
                $this->_root->expression = $element->getChild(0)->accept(
                    $this,
                    $handle,
                    $eldnah
                );

                return $this->_root;

            case '#operation':
                $children = $element->getChildren();
                $left     = $children[0]->accept($this, $handle, $eldnah);
                $right    = $children[2]->accept($this, $handle, $eldnah);
                $name     = $children[1]->accept($this, $handle, false);

                return $this->_root->_operator(
                    $name,
                    [$left, $right],
                    false
                );

            case '#variable_access':
                $children = $element->getChildren();
                $name     = $children[0]->accept($this, $handle, $eldnah);
                array_shift($children);

                foreach ($children as $child) {
                    $_child = $child->accept($this, $handle, $eldnah);

                    switch ($child->getId()) {
                        case '#array_access':
                            $name->index($_child);

                            break;

                        case '#attribute_access':
                            $name->attribute($_child);

                            break;

                        case '#method_access':
                            $name->call($_child);

                            break;
                    }
                }

                return $name;

            case '#array_access':
                return $element->getChild(0)->accept($this, $handle, $eldnah);

            case '#attribute_access':
                return $element->getChild(0)->accept($this, $handle, false);

            case '#method_access':
                return $element->getChild(0)->accept($this, $handle, $eldnah);

            case '#array_declaration':
                $out = [];

                foreach ($element->getChildren() as $child) {
                    $out[] = $child->accept($this, $handle, $eldnah);
                }

                return $out;

            case '#function_call':
                $children = $element->getChildren();
                $name     = $children[0]->accept($this, $handle, false);
                array_shift($children);

                $arguments = [];

                foreach ($children as $child) {
                    $arguments[] = $child->accept($this, $handle, $eldnah);
                }

                return $this->_root->_operator(
                    $name,
                    $arguments,
                    true
                );

            case '#and':
            case '#or':
            case '#xor':
                $name     = substr($id, 1);
                $children = $element->getChildren();
                $left     = $children[0]->accept($this, $handle, $eldnah);
                $right    = $children[1]->accept($this, $handle, $eldnah);

                return $this->_root->operation(
                    $name,
                    [$left, $right]
                );

            case '#not':
                return $this->_root->operation(
                    'not',
                    [$element->getChild(0)->accept($this, $handle, $eldnah)]
                );

            case 'token':
                $token = $element->getValueToken();
                $value = $element->getValueValue();

                switch ($token) {
                    case 'identifier':
                        return
                            true === $variable
                                ? $this->_root->variable($value)
                                : $value;

                    case 'true':
                        return true;

                    case 'false':
                        return false;

                    case 'null':
                        return null;

                    case 'float':
                        return floatval($value);

                    case 'integer':
                        return intval($value);

                    case 'string':
                        return str_replace(
                            '\\' . $value[0],
                            $value[0],
                            substr($value, 1, -1)
                        );

                    default:
                        throw new Ruler\Exception\Interpreter(
                            'Token %s is unknown.',
                            0,
                            $token
                        );
                }

                break;

            default:
                throw new Ruler\Exception\Interpreter(
                    'Element %s is unknown.',
                    1,
                    $id
                );
        }

        return;
    }

    /**
     * Get root.
     *
     * @return  \Hoa\Ruler\Model
     */
    public function getRoot()
    {
        return $this->_root;
    }
}
