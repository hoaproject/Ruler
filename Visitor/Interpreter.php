<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2014, Ivan Enderlin. All rights reserved.
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

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Exception\Interpreter
 */
-> import('Ruler.Exception.Interpreter')

/**
 * \Hoa\Ruler\Model
 */
-> import('Ruler.Model.~')

/**
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

}

namespace Hoa\Ruler\Visitor {

/**
 * Class \Hoa\Ruler\Visitor\Interpreter.
 *
 * Interpreter: rule to model.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Interpreter implements \Hoa\Visitor\Visit {

    /**
     * Root.
     *
     * @var \Hoa\Ruler\Model object
     */
    protected $_root    = null;

    /**
     * Current node.
     *
     * @var \Hoa\Ruler\Visitor\Interpreter object
     */
    protected $_current = null;



    /**
     * Visit an element.
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     * @throw   \Hoa\Ruler\Exception\Interpreter
     */
    public function visit ( \Hoa\Visitor\Element $element, &$handle = null, $eldnah = null ) {

        $id       = $element->getId();
        $variable = false !== $eldnah;

        switch($id) {

            case '#expression':
                $this->_root             = new \Hoa\Ruler\Model();
                $this->_root->expression = $element->getChild(0)->accept(
                    $this,
                    $handle,
                    $eldnah
                );

                return $this->_root;
              break;

            case '#operation':
                $children = $element->getChildren();
                $left     = $children[0]->accept($this, $handle, $eldnah);
                $right    = $children[2]->accept($this, $handle, $eldnah);
                $name     = $children[1]->accept($this, $handle, false);

                return $this->_root->_operator(
                    $name,
                    array($left, $right),
                    false
                );
              break;

            case '#array_access':
                $children = $element->getChildren();
                $name     = $children[0]->accept($this, $handle, $eldnah);
                array_shift($children);

                foreach($children as $child)
                    $name->index($child->accept($this, $handle, $eldnah));

                return $name;
              break;

            case '#array_declaration':
                $out = array();

                foreach($element->getChildren() as $child)
                    $out[] = $child->accept($this, $handle, $eldnah);

                return $out;
              break;

            case '#function_call':
                $children = $element->getChildren();
                $name     = $children[0]->accept($this, $handle, false);
                array_shift($children);

                $arguments = array();

                foreach($children as $child)
                    $arguments[] = $child->accept($this, $handle, $eldnah);

                return $this->_root->_operator(
                    $name,
                    $arguments,
                    true
                );
              break;

            case '#and':
            case '#or':
            case '#xor':
                $name     = substr($id, 1);
                $children = $element->getChildren();
                $left     = $children[0]->accept($this, $handle, $eldnah);
                $right    = $children[1]->accept($this, $handle, $eldnah);

                return $this->_root->operation(
                    $name,
                    array($left, $right)
                );

            case '#not':
                return $this->_root->operation(
                    'not',
                    array($element->getChild(0)->accept($this, $handle, $eldnah))
                );

            case 'token':
                $token = $element->getValueToken();
                $value = $element->getValueValue();

                switch($token) {

                    case 'identifier':
                        return true === $variable
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
                        throw new \Hoa\Ruler\Exception\Interpreter(
                            'Token %s is unknown.', 0, $token);
                }
              break;

            default:
                throw new \Hoa\Ruler\Exception\Interpreter(
                    'Element %s is unknown.', 1, $id);
        }

        return;
    }

    /**
     * Get root.
     *
     * @access  public
     * @return  \Hoa\Ruler\Model
     */
    public function getRoot ( ) {

        return $this->_root;
    }
}

}
