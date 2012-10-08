<?php
namespace phprules\operators;

use phprules\RuleElement;

/**
 * An interface for pluggable operators.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
interface OperatorInterface
{
    /**
     * Get a list of operator symbols.
     *
     * @return array List of operator strings.
     */
    public static function getSymbols();

    /**
     * Perform a operation on the given stack.
     *
     * @param string $operator The operator symbol.
     * @param array $stack The stack.
     * @return array The updated stack.
     */
    public static function perform($operator, $stack);

}
