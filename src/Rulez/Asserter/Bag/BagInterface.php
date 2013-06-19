<?php

namespace Rulez\Asserter\Bag;

use Rulez\Asserter\Context;

/**
 * BagInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface BagInterface {

    /**
     * @return string
     */
    public function __toString ( );

    /**
     * @param Context $context context
     */
    public function transform ( Context $context );
}
