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
 * \Hoa\Ruler\Exception\Asserter
 */
-> import('Ruler.Exception.Asserter')

/**
 * \Hoa\Ruler\Model\Bag
 */
-> import('Ruler.Model.Bag.~');

}

namespace Hoa\Ruler\Model\Bag {

/**
 * Class \Hoa\Ruler\Model\Bag\Context.
 *
 * Bag for context object.
 *
 * @author     Stéphane Py <stephane.py@hoa-project.net>
 * @author     Ivan Enderlin <ivan.enderlin@hoa-project.net>
 * @copyright  Copyright © 2007-2014 Stéphane Py, Ivan Enderlin.
 * @license    New BSD License
 */

class Context extends Bag {

    /**
     * ID.
     *
     * @var \Hoa\Ruler\Bag\Context string
     */
    protected $_id    = null;

    /**
     * Value.
     *
     * @var \Hoa\Ruler\Bag\Context string
     */
    protected $_value = null;



    /**
     * Constructor.
     *
     * @access  public
     * @param   string  $id    ID.
     * @return  void
     */
    public function __construct ( $id ) {

        $this->_id = $id;

        return;
    }

    /**
     * Get ID.
     *
     * @access  public
     * @return  string
     */
    public function getId ( ) {

        return $this->_id;
    }

    /**
     * Transform a context to fit in the bag.
     *
     * @access  public
     * @param   \Hoa\Ruler\Context  $context    Context.
     * @return  string
     * @throw   \Hoa\Ruler\Exception\UnknownContext
     */
    public function transform ( \Hoa\Ruler\Context $context ) {

        $id = $this->getId();

        if(!isset($context[$id]))
            throw new \Hoa\Ruler\Exception\Asserter(
                'Context reference %s does not exists.', 0, $id);

        return $this->_value = $context[$id];
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
