<?php
namespace phprules;

/**
 * Represents a Proposition in formal logic, a statement with at truth value.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class Proposition extends RuleElement
{
    /**
     * The Boolean truth value of the Proposition.
     *
     * @var boolean
     */
    public $value;

    /**
     * Create new instance.
     *
     * @param string  $name       The name.
     * @param boolean $truthValue The truth value.
     */
    public function __construct($name, $truthValue)
    {
        parent::__construct($name);
        $this->value = $truthValue;
    }

    /**
     * Get the truth value of this proposition.
     *
     * @return boolean The truth value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the truth value of this proposition.
     *
     * @param boolean value The truth value.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Performs a Boolean AND operation on another {@link Proposition}
     *
     * @param  Proposition $proposition
     * @return Proposition
     */
    public function logicalAnd(Proposition $proposition)
    {
        $resultName  = "( " . $this->getName() . " AND " . $proposition->getName() . " )";
        $resultValue = ($this->value and $proposition->value);

        return new Proposition($resultName, $resultValue);
    }

    /**
     * Performs a Boolean OR operation on another {@link Proposition}
     *
     * @param  Proposition $proposition
     * @return Proposition
     */
    public function logicalOr(Proposition $proposition)
    {
        $resultName  = "( " . $this->getName() . " OR " . $proposition->getName() . " )";
        $resultValue = ($this->value or $proposition->value);

        return new Proposition( $resultName, $resultValue );
    }

    /**
     * Performs a Boolean NOT operation its own value
     *
     * @return Proposition
     */
    public function logicalNot()
    {
        $resultName  = "( NOT " . $this->getName() . " )";
        $resultValue = (!$this->value);

        return new Proposition($resultName, $resultValue);
    }

    /**
     * Performs a Boolean XOR operation on another {@link Proposition}
     *
     * @param  Proposition $proposition
     * @return Proposition
     */
    public function logicalXor(Proposition $proposition)
    {
        $resultName  = "( " . $this->getName() . " XOR " . $proposition->getName() . " )";
        $resultValue = ($this->value xor $proposition->value);

        return new Proposition($resultName, $resultValue);
    }

    /**
     * Returns a human-readable statement and value.
     *
     * @return string
     */
    public function __toString()
    {
        return "[Proposition statement=" . $this->getName() . ", value=" . ($this->getValue() ? "true" : "false") . "]";
    }

}
