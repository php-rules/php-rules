<?php
namespace phprules;

/**
 * A set of rules.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class RuleSet {
    private $name;
    private $rules;
    private $ruleOverrides;


    /**
     * Create new rule set.
     *
     * @param string name The name.
     */
    public function __construct($name) {
        $this->name = $name;
        $this->rules = array();
        $this->ruleOverrides = array();
    }

    /**
     * Add rule.
     *
     * @param Rule $rule The rule to add.
     */
    public function addRule(Rule $rule) {
        $this->rules[] = $rule;
    }

    /**
     * Add an override rule.
     *
     * @param RuleOverride $ruleOverride The override rule.
     */
    public function addRuleOverride(RuleOverride $ruleOverride) {
        $this->ruleOverrides[] = $ruleOverride;
    }

    /**
     * Evaluate this rule set.
     *
     * @param RuleContext $ruleContext The context.
     */
    public function evaluate($ruleContext) {
        // Each Rule in the RuleSet is evaluated, and the
        // results AND'ed together taking account of any RuleOverrides
        $resultsForRules = array();
        // Accumulate the results of evaluating the Rules
        foreach ($this->rules as $rule) {
            $result = $rule->evaluate($ruleContext);
            $resultsForRules[$rule->name] = $result;
        }

        // Apply the RuleOverrides
        foreach ($this->ruleOverrides as $ruleOverride) {
            $result = $resultsForRules[$ruleOverride->ruleName];
            if ($result) {
                $result->value = $ruleOverride->value;
            }
        }

        // Work out the final result
        $finalResult = true;
        foreach ($resultsForRules as $name => $result) {
            $finalResult = ($finalResult && $result->getValue());
        }

        return new Proposition($this->name, $finalResult);
    }

}
