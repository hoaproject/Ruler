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
 * \Hoa\Ruler\Model\Bag
 */
-> import('Ruler.Model.Bag.~')

/**
 * \Hoa\Ruler\Model\Bag\Scalar
 */
-> import('Ruler.Model.Bag.Scalar');

}

namespace Hoa\Ruler\Model\Bag {

/**
 * Class \Hoa\Ruler\Model\Bag\_Array.
 *
 * Bag for an array.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2013 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class _Array extends Bag {

    /**
     * Array.
     *
     * @var \Hoa\Ruler\Model\Bag\_Array array
     */
    protected $_array = null;

    /**
     * Value.
     *
     * @var \Hoa\Ruler\Model\Bag\_Array array
     */
    protected $_value = null;



    /**
     * Constructor.
     *
     * @access  public
     * @param   array  $data    Data.
     * @return  void
     */
    public function __construct ( Array $data ) {

        foreach($data as &$datum) {

            if($datum instanceof Bag)
                continue;

            if(is_scalar($datum) || null === $datum)
                $datum = new Scalar($datum);
            elseif(is_array($datum))
                $datum = new static($datum);
        }

        $this->_array = $data;

        return;
    }

    /**
     * Get array.
     *
     * @access  public
     * @return  array
     */
    public function getArray ( ) {

        return $this->_array;
    }

    /**
     * Transform a context to fit in the bag.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  array
     */
    public function transform ( \Hoa\Ruler\Context $context ) {

        foreach($this->_array as $key => $data)
            $this->_value[$key] = $data->transform($context);

        return $this->_value;
    }

    /**
     * Get content of the bag.
     *
     * @access  public
     * @return  mixed
     */
    public function getValue ( ) {

        return $this->_value;
    }
}

}
