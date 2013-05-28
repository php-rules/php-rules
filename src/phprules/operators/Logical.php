<?php
namespace phprules\operators;

use UnexpectedValueException;
use phprules\Proposition;

/**
 * Logical operations.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class Logical implements OperatorInterface
{
    const LOGICAL_OR  = 'OR';
    const LOGICAL_AND = 'AND';
    const LOGICAL_XOR = 'XOR';
    const LOGICAL_NOT = 'NOT';

    /**
     * {@inheritDoc}
     */
    public static function getSymbols()
    {
        return array(
            static::LOGICAL_OR,
            static::LOGICAL_AND,
            static::LOGICAL_XOR,
            static::LOGICAL_NOT,
        );
    }

    /**
     * {@inheritDoc}
     */
    public static function perform($operator, $stack)
    {
        $rhs = array_pop($stack);
        if (!($rhs instanceof Proposition)) {
            throw new UnexpectedValueException('rhs Proposition required');
        }

        if (static::LOGICAL_NOT != $operator) {
            $lhs = array_pop($stack);
            if (!($lhs instanceof Proposition)) {
                throw new UnexpectedValueException('lhs Proposition required');
            }
        }

        switch ($operator) {
        case static::LOGICAL_OR:
            $name = sprintf('( %s OR %s )', $lhs->getName(), $rhs->getName());
            $stack[] = new Proposition($name, $lhs->getValue() or $rhs->getValue());
            break;
        case static::LOGICAL_AND:
            $name = sprintf('( %s AND %s )', $lhs->getName(), $rhs->getName());
            $stack[] = new Proposition($name, $lhs->getValue() and $rhs->getValue());
            break;
        case static::LOGICAL_XOR:
            $name = sprintf('( %s XOR %s )', $lhs->getName(), $rhs->getName());
            $stack[] = new Proposition($name, $lhs->getValue() xor $rhs->getValue());
            break;
        case static::LOGICAL_NOT:
            $name = sprintf('( NOT %s )', $rhs->getName());
            $stack[] = new Proposition($name, !$rhs->getValue());
            break;
        }

        return $stack;
    }

}
