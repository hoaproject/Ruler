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
 * \Hoa\Ruler\Comparator\*
 */
-> import('Ruler.Comparator.*')

/**
 * \Hoa\Ruler\LogicalOperator\LogicalOperatorInterface
 */
-> import('Ruler.LogicalOperator.LogicalOperatorInterface')

/**
 * \Hoa\Ruler\Visitor\DecodeVisitor
 */
-> import('Ruler.Visitor.DecodeVisitor');

}

namespace Hoa\Ruler {

/**
 * Ruler
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Ruler {

    /**
     * @var \Hoa\Compiler\Llk\Parser|null
     */
    protected $compiler;

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

        $this->addComparator('=', '\Hoa\Ruler\Comparator\Equal');
        $this->addComparator('!=', '\Hoa\Ruler\Comparator\NotEqual');
        $this->addComparator('>', '\Hoa\Ruler\Comparator\GreaterThan');
        $this->addComparator('>=', '\Hoa\Ruler\Comparator\GreaterThanEqual');
        $this->addComparator('<', '\Hoa\Ruler\Comparator\LessThan');
        $this->addComparator('<=', '\Hoa\Ruler\Comparator\LessThanEqual');
        $this->addComparator('IS', '\Hoa\Ruler\Comparator\Is');
        $this->addComparator('IS NOT', '\Hoa\Ruler\Comparator\IsNot');
        $this->addComparator('IN', '\Hoa\Ruler\Comparator\In');
    }

    /**
     * Decode string as Condition(s)|Operator(s)
     *
     * @param string               $str
     *
     * @return LogicalOperator\LogicalOperatorInterface|Comparator\ComparatorInterface
     */
    public function decode ( $str ) {

        $compiler = $this->getCompiler();
        $ast      = $compiler->parse($str);

        $visitor = new Visitor\DecodeVisitor($this);

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
            $data = $this->decode($data);

        if (!$data instanceof LogicalOperator\LogicalOperatorInterface && !$data instanceof Comparator\ComparatorInterface)
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

        if (null === $this->compiler) {
            $read           = new \Hoa\File\Read(__DIR__.'/Grammar.pp');
            $this->compiler = \Hoa\Compiler\Llk::load($read);
        }

        return $this->compiler;
    }
}

}
