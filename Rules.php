<?php
namespace Hoa\Ruler;

use Hoa\Ruler\Exception\NoResult;
use Hoa\Ruler\Exception\RuleDoesNotValidate;

class Rules
{
    /**
     * @var \SplPriorityQueue
     */
    private $queue;

    public function __construct()
    {
        $this->queue = new \SplPriorityQueue();
    }

    /**
     * @param string $name
     * @param Rule   $rule
     * @param int    $priority
     *
     * @return Rules
     */
    public function add($name, Rule $rule, $priority = -1)
    {
        $rules = clone $this;

        $rules->queue->insert([$name, $rule], $priority);

        return $rules;
    }

    /**
     * @param Ruler   $ruler
     * @param Context $context
     *
     * @throws NoResult
     *
     * @return Result
     */
    public function getBestResult(Ruler $ruler, Context $context)
    {
        $queue = clone $this->queue;

        foreach ($queue as $nameAndRule) {
            /**
             * @var string $name
             * @var Rule   $rule
             */
            list($name, $rule) = $nameAndRule;

            try {
                $context[$name] = $rule->execute($ruler, $context);
            } catch (RuleDoesNotValidate $exception) {
                $context[$name] = null;
            }

            if ($rule->valid($ruler, $context)) {
                return new Result($name, $rule, $context[$name]);
            }
        }

        throw new NoResult();
    }

    /**
     * @param Ruler $ruler
     * @param array $context
     *
     * @return Result[]
     */
    public function getAllResults(Ruler $ruler, Context $context)
    {
        $queue    = clone $this->queue;
        $outcomes = [];

        foreach ($queue as $nameAndRule) {
            /**
             * @var string $name
             * @var Rule   $rule
             */
            list($name, $rule) = $nameAndRule;

            try {
                $context[$name] = $rule->execute($ruler, $context);
            } catch (RuleDoesNotValidate $exception) {
                $context[$name] = null;
            }

            $outcomes[] = new Result($name, $rule, $context[$name]);
        }

        return $outcomes;
    }
}
