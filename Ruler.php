<?php

namespace {

from('Hoa')

/**
 * \Hoa\Compiler\Llk
 */
-> import('Compiler.Llk')

/**
 * \Hoa\Compiler\Llk\Parser
 */
-> import('Compiler.Llk.Parser')

/**
 * \Hoa\File\Read
 */
-> import('File.Read')

/**
 * \Hoa\Ruler\Asserter\Context
 */
-> import('Ruler.Asserter.Context')

/**
 * \Hoa\Ruler\Model\Comparator\*
 */
-> import('Ruler.Model.Comparator.*')

/**
 * \Hoa\Ruler\Model\Operator\LogicalInterface
 */
-> import('Ruler.Model.Operator.LogicalInterface')

/**
 * \Hoa\Ruler\Visitor\Interpreter
 */
-> import('Ruler.Visitor.Interpreter');

}

namespace Hoa\Ruler {

/**
 * Ruler
 *
 * @author Stephane PY <stephane.py@hoa-project.net>
 */
class Ruler {

    /**
     * @var \Hoa\Compiler\Llk\Parser|null
     */
    protected static $compiler;

    /**
     * @var array
     */
    protected $comparators = array();

    /**
     * @var array
     */
    protected $functions = array();

    /**
     * @param array $comparators comparators
     */
    public function __construct ( array $comparators = array() ) {

        $this->addComparator('=', '\Hoa\Ruler\Model\Comparator\Equal');
        $this->addComparator('!=', '\Hoa\Ruler\Model\Comparator\NotEqual');
        $this->addComparator('>', '\Hoa\Ruler\Model\Comparator\GreaterThan');
        $this->addComparator('>=', '\Hoa\Ruler\Model\Comparator\GreaterThanEqual');
        $this->addComparator('<', '\Hoa\Ruler\Model\Comparator\LessThan');
        $this->addComparator('<=', '\Hoa\Ruler\Model\Comparator\LessThanEqual');
        $this->addComparator('IS', '\Hoa\Ruler\Model\Comparator\Is');
        $this->addComparator('IS NOT', '\Hoa\Ruler\Model\Comparator\IsNot');
        $this->addComparator('IN', '\Hoa\Ruler\Model\Comparator\In');

        $this->addFunction('date', function(array $arguments) {
            if (count($arguments) > 2) {
                throw new \InvalidArgumentException('Date function accepts 2 arguments maximum');
            }

            if (!isset($arguments[1])) {
                $arguments[1] = new \DateTime();
            } elseif (is_scalar($arguments[1])) {
                $arguments[1] = new \DateTime($arguments[1]);
            } elseif (!$arguments[1] instanceof \DateTime) {
                throw new \InvalidArgumentException('date function accepts in second argument: scalar, \DateTime');
            }

            return $arguments[1]->format($arguments[0]);
        });
    }

    /**
     * Interprete string as Condition(s)|Operator(s)
     *
     * @param string               $str
     *
     * @return Model\Operator\LogicalInterface|Model\Comparator\ComparatorInterface
     */
    public function interprete( $str ) {

        $compiler = $this->getCompiler();
        $ast      = $compiler->parse($str);

        $visitor = new Visitor\Interpreter($this);

        return $visitor->visit($ast);
    }

    /**
     * @param string|object               $data    data
     * @param \Hoa\Ruler\Asserter\Context $context context
     *
     * @return boolean
     */
    public function assert ( $data, \Hoa\Ruler\Asserter\Context $context ) {

        if (is_scalar($data))
            $data = $this->interprete($data);

        if (!$data instanceof Model\Operator\LogicalInterface && !$data instanceof Model\Comparator\ComparatorInterface)
            throw new \InvalidArgumentException('Ruler can encode only comparators or logical operators');

        $context->setRuler($this);
        $data->transform($context);

        return $data->assert();
    }

    /**
     * @param string $token token
     * @param string $class class
     *
     * @return Ruler
     */
    public function addComparator ( $token, $class ) {

        $this->comparators[$token] = $class;

        return $this;
    }

    /**
     * @param string $token token
     *
     * @return boolean
     */
    public function hasComparator ( $token ) {

        return array_key_exists((string) $token, $this->comparators);
    }

    /**
     * @param string $token token
     *
     * @return string|null
     */
    public function getComparator ( $token ) {

        return $this->hasComparator($token) ? $this->comparators[$token] : null;
    }

    /**
     * @param string   $name    name
     * @param \Closure $closure closure
     *
     * @return Ruler
     */
    public function addFunction ( $name, \Closure $closure ) {

        $this->functions[$name] = $closure;

        return $this;
    }

    /**
     * @param string $name name
     *
     * @return boolean
     */
    public function hasFunction ( $name ) {

        return array_key_exists((string) $name, $this->functions);
    }

    /**
     * @param string $name name
     *
     * @return \Closure|null
     */
    public function getFunction ( $name ) {

        return $this->hasFunction($name) ? $this->functions[$name] : null;
    }

    /**
     * @return \Hoa\Compiler\Llk\Parser
     */
    protected function getCompiler ( ) {

        if (!self::$compiler) {
            $read           = new \Hoa\File\Read(__DIR__.'/Grammar.pp');
            self::$compiler = \Hoa\Compiler\Llk::load($read);
        }

        return self::$compiler;
    }
}

}
