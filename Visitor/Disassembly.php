<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2013, Ivan Enderlin. All rights reserved.
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
 * Class \Hoa\Ruler\Visitor\Disassembly.
 *
 * Disassembly: rule model to rule as a regular string.
 *
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Ivan Enderlin, Stéphane Py
 * @license    New BSD License
 */

class Disassembly implements \Hoa\Visitor\Visit {

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

        if($element instanceof \Hoa\Ruler\Model) {

            $out .= $element->getExpression()->accept($this, $handle, $eldnah);
        }
        elseif($element instanceof \Hoa\Ruler\Model\Operator) {

            $name      = $element->getName();
            $arguments = array();

            foreach($element->getArguments() as $argument)
                $arguments[] = $argument->accept($this, $handle, $eldnah);

            if(true === $element->isFunction())
                $out .= $name . '(' . implode(', ', $arguments) . ')';
            else {

                $_out = $arguments[0] . ' ' . $name . ' ' . $arguments[1];

                if(false === \Hoa\Ruler\Model\Operator::isToken($name))
                    $_out = '(' . $_out . ')';

                $out .= $_out;
            }

        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Scalar) {

            $value = $element->getValue();

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
        elseif($element instanceof \Hoa\Ruler\Model\Bag\_Array) {

            $values = array();

            foreach($element->getArray() as $value)
                $values[] = $value->accept($this, $handle, $eldnah);

            $out .= '(' . implode(', ', $values) . ')';
        }
        elseif($element instanceof \Hoa\Ruler\Model\Bag\Context) {

            $out .= $element->getId();
        }

        return $out;
    }
}

}
