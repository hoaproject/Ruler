<?php
namespace Hoa\Ruler\Rules;

use Hoa\Ruler\Context;
use Hoa\Ruler\Exception\RuleDoesNotValidate;
use Hoa\Ruler\Model\Model;
use Hoa\Ruler\Rule;
use Hoa\Ruler\Ruler;

class IfRule implements Rule
{
    /**
     * @var string|Model
     */
    private $rule;

    /**
     * @var mixed
     */
    private $result;

    /**
     * @param string $rule
     * @param mixed  $result
     */
    public function __construct($rule, $result)
    {
        $this->rule   = $rule;
        $this->result = $result;
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return bool
     */
    public function valid(Ruler $ruler, Context $context)
    {
        return $ruler->assert($this->rule, $context);
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return mixed
     */
    public function execute(Ruler $ruler, Context $context)
    {
        if ($this->valid($ruler, $context)) {
            return $this->result;
        }

        throw new RuleDoesNotValidate('Rule ' . $this->rule . ' does not validate');
    }
}
