<?php

namespace Rulez\Comparator;

use Rulez\Asserter\Context;
use Rulez\Exception\InvalidValueException;

/**
 * ComparatorInterface
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
interface ComparatorInterface {
    /**
     * @throws InvalidValueException
     *
     * @return boolean
     */
    public function assert ( );

    /**
     * @param Context $context context
     */
    public function transform( Context $context );

    /**
     * @return string
     */
    public function getToken ( );
}
