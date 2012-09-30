<?php
namespace phprules;

/**
 * Rule override.
 *
 * <p>Allows to override the result of a rule as denoted by the given <code>$ruleName</code>.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class RuleOverride
{
    private $name;
    private $ruleName;
    private $value;

    /**
     * Create new rule override.
     *
     * @param string  $name     The rule override name.
     * @param string  $ruleName The rule name.
     * @param boolean $value    The value.
     */
    public function __construct($name, $ruleName, $value)
    {
        $this->name = $name;
        $this->ruleName = $ruleName;
        $this->value = $value;
    }

    /**
     * Get the name of this rule override.
     *
     * @return string The rule override name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get the name of the rule to override.
     *
     * @return string The rule name.
     */
    public function getRuleName()
    {
        return $this->ruleName;
    }

    /**
     * Get the override value.
     *
     * @return mixed The override value.
     */
    public function getValue()
    {
        return $this->value;
    }

}
