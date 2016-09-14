<?php
namespace Hoa\Ruler\Rules;

use Hoa\Ruler\Context;
use Hoa\Ruler\Exception\RuleDoesNotValidate;
use Hoa\Ruler\Rule;
use Hoa\Ruler\Ruler;

class IfElseRule implements Rule
{
    /**
     * @var IfRule
     */
    private $rule;

    /**
     * @var mixed
     */
    private $otherwise;

    /**
     * @param string $rule
     * @param mixed  $result
     * @param mixed  $otherwise
     */
    public function __construct($rule, $result, $otherwise)
    {
        $this->rule      = new IfRule($rule, $result);
        $this->otherwise = $otherwise;
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return bool
     */
    public function valid(Ruler $ruler, Context $context)
    {
        return $this->rule->valid($ruler, $context);
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @return mixed
     */
    public function execute(Ruler $ruler, Context $context)
    {
        try {
            return $this->rule->execute($ruler, $context);
        } catch (RuleDoesNotValidate $e) {
            return $this->otherwise;
        }
    }
}
