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
            $rhsValue = $rhs->getValue();
            if (is_string($rhsValue)) {
                $rhsValue = explode(',', $rhsValue);
            }
            $name = sprintf('( %s IN ( %s ) )', $lhs->getName(), implode(',', $rhsValue));
            $stack[] = new Proposition($name, in_array($lhs->getValue(), $rhsValue));
            break;
        }

        return $stack;
    }

}
