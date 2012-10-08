<?php
namespace phprules\operators;

use UnexpectedValueException;
use phprules\Proposition;
use phprules\Variable;

/**
 * In operation.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class In implements OperatorInterface
{
    const IN = 'IN';

    /**
     * {@inheritDoc}
     */
    public static function getSymbols()
    {
        return array(
            static::IN,
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function perform($operator, $stack)
    {
        $rhs = array_pop($stack);
        if (!($rhs instanceof Variable)) {
            throw new UnexpectedValueException('rhs Variable required');
        }

        $lhs = array_pop($stack);
        if (!($lhs instanceof Variable)) {
            throw new UnexpectedValueException('lhs Variable required');
        }

        switch ($operator) {
        case static::IN:
            $name = sprintf('( %s IN ( %s ) )', $rhs->getStatementName(), $lhs->getName());
            $stack[] = new Proposition($name, is_array($lhs->getValue()) && in_array($rhs->getValue(), $lhs->getValue()));
            break;
        }

        return $stack;
    }

}
