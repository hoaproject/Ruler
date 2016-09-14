<?php
namespace Hoa\Ruler;

interface Rule
{
    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return bool
     */
    public function valid(Ruler $ruler, Context $context);

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return mixed
     */
    public function execute(Ruler $ruler, Context $context);
}
