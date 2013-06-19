<?php

namespace Rulez;

use Hoa\Compiler\Llk\Parser;
use Rulez\Asserter\Context;
use Rulez\Comparator\ComparatorInterface;
use Rulez\LogicalOperator\LogicalOperatourInterface;

/**
 * Ruler
 *
 * @author Stephane PY <py.stephane1@gmail.com>
 */
class Ruler {

    /**
     * @var Parser|null
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

        $this->addComparator('=', '\Rulez\Comparator\Equal');
        $this->addComparator('!=', '\Rulez\Comparator\NotEqual');
        $this->addComparator('>', '\Rulez\Comparator\GreaterThan');
        $this->addComparator('>=', '\Rulez\Comparator\GreaterThanEqual');
        $this->addComparator('<', '\Rulez\Comparator\LessThan');
        $this->addComparator('<=', '\Rulez\Comparator\LessThanEqual');
        $this->addComparator('IS', '\Rulez\Comparator\Is');
        $this->addComparator('IS NOT', '\Rulez\Comparator\IsNot');
        $this->addComparator('IN', '\Rulez\Comparator\In');
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

        /*$dump = new \Hoa\Compiler\Visitor\Dump();
        echo $dump->visit($ast);
        exit('ici');*/

        $visitor = new Visitor\DecodeVisitor($this);

        return $visitor->visit($ast);
    }

    /**
     * @param string|LogicalOperatorInterface|ComparatorInterface| $data    data
     * @param Context                                              $context context
     *
     * @return boolean
     */
    public function assert ( $data, Context $context ) {

        if (is_scalar($data))
            $data = $this->decode($data);

        if (!$data instanceof LogicalOperatorInterface && !$data instanceof ComparatorInterface)
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
     * @return Parser
     */
    protected function getCompiler ( ) {

        if (null === $this->compiler) {
            from('Hoa')
                ->import('Compiler.Llk')
                ->import('File.Read')
                ->import('Compiler.Visitor.Dump')
                ;

            $read           = new \Hoa\File\Read(__DIR__.'/Grammar.pp');
            $this->compiler = \Hoa\Compiler\Llk::load($read);
        }

        return $this->compiler;
    }
}
