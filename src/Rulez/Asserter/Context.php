<?php

namespace Rulez\Asserter;

use Rulez\Ruler;

/**
 * Context
 *
 * @uses \Pimple
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Context extends \Pimple
{
    /**
     * @var Ruler
     */
    private $ruler;

    /**
     * @param Ruler $ruler ruler
     */
    public function setRuler(Ruler $ruler)
    {
        $this->ruler = $ruler;
    }

    /**
     * @return Ruler
     */
    public function getRuler()
    {
        return $this->ruler;
    }
}
