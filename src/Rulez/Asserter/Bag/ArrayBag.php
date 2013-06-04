<?php

namespace Rulez\Asserter\Bag;

use Rulez\Asserter\Context;

/**
 * ArrayBag
 *
 * @uses BagInterface
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class ArrayBag implements BagInterface
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param array $data data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return sprintf('[%s]', implode(', ', $this->data));
    }

    /**
     * {@inheritdoc}
     */
    public function transform(Context $context)
    {
        foreach ($this->data as $k => $data) {
            if ($data instanceof BagInterface) {
                $this->data[$k] = $data->transform($context);
            }
        }

        return $this->data;
    }
}
