<?php
namespace phprules\operators;

use UnexpectedValueException;
use phprules\Proposition;
use phprules\Variable;

/**
 * Comparison operations.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class Comparison implements OperatorInterface
{
    const EQUAL_TO                 = 'EQUALTO';
    const LESS_THAN                = 'LESSTHAN';
    const LESS_THAN_OR_EQUAL_TO    = 'LESSTHANOREQUALTO';
    const GREATER_THAN             = 'GREATERTHAN';
    const GREATER_THAN_OR_EQUAL_TO = 'GREATERTHANOREQUALTO';

    /**
     * {@inheritDoc}
     */
    public static function getSymbols()
    {
        return array(
            static::EQUAL_TO,
            static::LESS_THAN,
            static::LESS_THAN_OR_EQUAL_TO,
            static::GREATER_THAN,
            static::GREATER_THAN_OR_EQUAL_TO,
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
        case static::EQUAL_TO:
            $name = sprintf('( %s == %s )', $lhs->getStatementName(), $rhs->getStatementName());
            $stack[] = new Proposition($name, $lhs->getValue() == $rhs->getValue());
            break;
        case static::LESS_THAN:
            $name = sprintf('( %s < %s )', $lhs->getStatementName(), $rhs->getStatementName());
            $stack[] = new Proposition($name, $lhs->getValue() < $rhs->getValue());
            break;
        case static::LESS_THAN_OR_EQUAL_TO:
            $name = sprintf('( %s <= %s )', $lhs->getStatementName(), $rhs->getStatementName());
            $stack[] = new Proposition($name, $lhs->getValue() <= $rhs->getValue());
            break;
        case static::GREATER_THAN:
            $name = sprintf('( %s > %s )', $lhs->getStatementName(), $rhs->getStatementName());
            $stack[] = new Proposition($name, $lhs->getValue() > $rhs->getValue());
            break;
        case static::GREATER_THAN_OR_EQUAL_TO:
            $name = sprintf('( %s >= %s )', $lhs->getStatementName(), $rhs->getStatementName());
            $stack[] = new Proposition($name, $lhs->getValue() >= $rhs->getValue());
            break;
        }

        return $stack;
    }

}
