<?php

namespace Rulez;

abstract class Factory
{
    /**
     * Decode string as Condition(s)|Operator(s)
     *
     * @param string $str
     *
     * @return Condition|Operator
     */
    public static function decode($str)
    {
        from('Hoa')
            ->import('Compiler.Llk')
            ->import('File.Read')
            ->import('Compiler.Visitor.Dump')
            ;

        $compiler = \Hoa\Compiler\Llk::load(
            new \Hoa\File\Read(__DIR__.'/Grammar.pp')
        );

        $ast  = $compiler->parse($str);
        /*$dump = new \Hoa\Compiler\Visitor\Dump();
        echo $dump->visit($ast);
        exit('ici');*/

        $visitor = new Visitor\DecodeVisitor();

        return $visitor->visit($ast);
    }

    /**
     * Encode data as string
     *
     * @param Operator|Condition $data data
     *
     * @return string
     */
    public static function encode($data)
    {
        if (!$data instanceof Condition && !$data instanceof Operator) {
            throw new \LogicException('This factory can encode only conditions or operators');
        }

        $visitor = new Visitor\EncodeVisitor();

        return $visitor->visit($data);
    }
}
