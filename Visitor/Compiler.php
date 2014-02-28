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
 * \Hoa\Visitor\Visit
 */
-> import('Visitor.Visit');

}

namespace Hoa\Ruler\Visitor {

/**
 * Class \Hoa\Ruler\Visitor\Compiler.
 *
 * Compiler: rule model to PHP.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Compiler implements \Hoa\Visitor\Visit {

    /**
     * Indentation level.
     *
     * @var \Hoa\Ruler\Visitor\Compiler int
     */
    protected $_indentation = 0;



    /**
     * Visit an element.
     *
     * @access  public
     * @param   \Hoa\Visitor\Element  $element    Element to visit.
     * @param   mixed                 &$handle    Handle (reference).
     * @param   mixed                 $eldnah     Handle (not reference).
     * @return  mixed
     */
    public function visit ( \Hoa\Visitor\Element $element, &$handle = null, $eldnah = null ) {

        $out = null;
        $_   = str_repeat('    ', $this->_indentation);

        if($element instanceof \Hoa\Ruler\Model) {

            $this->_indentation = 1;

            $out = '$model = new \Hoa\Ruler\Model();' . "\n" .
                   '$model->expression =' . "\n" .
                   $element->getExpression()->accept($this, $handle, $eldnah) .
                   ';';
        }
        elseif($element instanceof \Hoa\Ruler\Model\Operator) {

            $out  = $_ . '$model->';
            $name = $element->getName();

            if(false === $element->isFunction()) {

                if(true === \Hoa\Core\Consistency::isIdentifier($name))
                    $out .= $name;
                else
                    $out .= '{\'' . $name . '\'}';

                $out     .= '(' . "\n";
            }
            else
                $out .= 'func(' . "\n" . $_ . '    ' .
                        '\'' . $name . '\',' . "\n";

            $_handle  = array();
            ++$this->_indentation;

            foreach($element->getArguments() as $argument)
                $_handle[] = $argument->accept($this, $handle, $eldnah);

            --$this->_indentation;

            $out .= implode(',' . "\n", $_handle) . "\n" . $_ . ')';
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Scalar) {

            $value = $element->getValue();
            $out   = $_;

            if(true === $value)
                $out .= 'true';
            elseif(false === $value)
                $out .= 'false';
            elseif(null === $value)
                $out .= 'null';
            elseif(is_numeric($value))
                $out .= (string) $value;
            else
                $out .= '\'' .
                       str_replace('\\', '\\\'', $value) .
                       '\'';
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\RulerArray) {

            $values = array();
            ++$this->_indentation;

            foreach($element->getArray() as $value)
                $values[] = $value->accept($this, $handle, $eldnah);

            --$this->_indentation;

            $out = $_ . 'array(' . "\n" . implode(',' . "\n", $values) . "\n" .
                   $_ . ')';
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Context) {

            $out = $_ . '$model->variable(\'' . $element->getId() . '\')';
            $this->_indentation += 2;

            foreach($element->getIndexes() as $index)
                $out .= "\n" . $_ . '    ->index(' . "\n" .
                            $index->accept($this, $handle, $eldnah) . "\n" .
                        $_ . '    )';

            $this->_indentation -= 2;
        }

        return $out;
    }
}

}
