<?php

namespace {

from('Hoa')

/**
 * \Hoa\Ruler\Ruler
 */
-> import('Ruler.Ruler');

}

namespace Hoa\Ruler\Asserter {

/**
 * Context
 *
 * @uses \Pimple
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Context extends \Pimple {

    /**
     * @var \Hoa\Ruler\Ruler
     */
    private $ruler;

    /**
     * @param \Hoa\Ruler\Ruler $ruler ruler
     */
    public function setRuler ( \Hoa\Ruler\Ruler $ruler ) {

        $this->ruler = $ruler;
    }

    /**
     * @return Ruler
     */
    public function getRuler ( ) {

        return $this->ruler;
    }
}

}
