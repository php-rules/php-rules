<?php
namespace phprules;

/**
 * Rule override.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class RuleOverride {
    public $ruleName;
    public $value;
    public $who;
    public $why;
    public $when;


    /**
     * Create new rule override.
     *
     * @param string $ruleName The rule name.
     * @param boolean $value The value.
     * @param mixed $who Who.
     * @param mixed $why Why.
     * @param mixed $when When.
     */
    public function __construct($ruleName, $value, $who, $why, $when) {
        $this->ruleName = $ruleName;
        $this->value = $value;
        $this->who = $who;
        $this->why = $why;
        $this->when = $when;
    }

}
