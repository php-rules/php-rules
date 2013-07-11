<?php
namespace phprules;

use InvalidArgumentException;

/**
 * Represents an operator with a boolean result.
 *
 * <p>New operators may be added by using the <code>addOperatorClass()</code> method.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class Operator extends RuleElement
{
    private static $DEFAULT_OPERATORS = array(
        'phprules\operators\Logical',
        'phprules\operators\Comparison',
        'phprules\operators\In',
    );
    private static $symbols = array();

    /**
     * Create new instance.
     *
     * @param string operator The operator.
     */
    public function __construct($operator)
    {
        parent::__construct($operator);
        if (!static::$symbols) {
            foreach (static::$DEFAULT_OPERATORS as $class) {
                static::addOperatorClass($class);
            }
        }
        if (!in_array($operator, array_keys(static::$symbols))) {
            throw new InvalidArgumentException($operator . " is not a valid operator.");
        }
    }

    /**
     * Add operator class.
     *
     * @param string class The implementation class.
     */
    public static function addOperatorClass($class)
    {
        foreach ($class::getSymbols() as $symbol) {
            static::$symbols[$symbol] = $class;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate($stack)
    {
        $class = static::$symbols[$this->getName()];

        return $class::perform($this->getName(), $stack);
    }

}
