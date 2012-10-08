<?php
namespace phprules;

/**
 * A symbol that represents a value.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class Variable extends RuleElement
{
    public $value;

    /**
     * Constructor initializes {@link $name}, and the {@link $value}.
     */
    public function __construct($name, $value)
    {
        parent::__construct($name);
        $this->value = $value;
    }

    /**
     * Get the truth value of this value.
     *
     * @return boolean The truth value.
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the name to be used in the statement.
     */
    public function getStatementName()
    {
        return $this->name ?: "'".$this->value."'";
    }

    /**
     * Set the truth value of this value.
     *
     * @param boolean value The truth value.
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Returns a human-readable statement and value.
     *
     * @return string
     */
    public function __toString()
    {
        return "[".get_class($this)." name=" . $this->getStatementName() . ", value=" . $this->value . "]";
    }

}
