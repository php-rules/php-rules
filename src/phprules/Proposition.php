<?php
namespace phprules;

/**
 * Represents a Proposition in formal logic, a statement with at truth value.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
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
     * @param string  $name  The name.
     * @param boolean $value The truth value.
     */
    public function __construct($name, $value)
    {
        parent::__construct($name);
        $this->value = $value;
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
     * Returns a human-readable statement and value.
     *
     * @return string
     */
    public function __toString()
    {
        return "[".get_class($this)." statement=" . $this->getName() . ", value=" . ($this->getValue() ? "true" : "false") . "]";
    }

}
