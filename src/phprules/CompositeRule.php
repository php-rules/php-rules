<?php
namespace phprules;

/**
 * A rule constisting of multiple rules.
 *
 * <p>The individual results of the containing rules are evaluated to a single result based on the given
 * evaluation policy.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class CompositeRule extends AbstractRule
{
    const EVALUATE_OR = 'EVALUATE_OR';
    const EVALUATE_AND = 'EVALUATE_AND';

    private $rules;
    private $evaluationPolicy;
    private $ruleOverrides;

    /**
     * Create new composite rule.
     *
     * @param string $name             The name.
     * @param string $evaluationPolicy The evaluation policy; default is <code>CompositeRule::EVALUATE_AND</code>.
     */
    public function __construct($name, $evaluationPolicy=self::EVALUATE_AND)
    {
        parent::__construct($name);
        $this->name = $name;
        $this->evaluationPolicy = $evaluationPolicy;
        $this->rules = array();
        $this->ruleOverrides = array();
    }

    /**
     * Add rule.
     *
     * @param Rule $rule The rule to add.
     */
    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * Add a rule override.
     *
     * @param RuleOverride $ruleOverride The rule override.
     */
    public function addRuleOverride(RuleOverride $ruleOverride)
    {
        $this->ruleOverrides[] = $ruleOverride;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(RuleContext $ruleContext)
    {
        $ruleResults = array();
        // accumulate the results of evaluating the Rules
        foreach ($this->rules as $rule) {
            $result = $rule->evaluate($ruleContext);
            $ruleResults[$rule->getName()] = $result;
        }

        // apply the RuleOverrides
        foreach ($this->ruleOverrides as $ruleOverride) {
            $ruleName = $ruleOverride->getRuleName();
            if (array_key_exists($ruleName, $ruleResults)) {
                $ruleResults[$ruleName]->setValue($ruleOverride->getValue());
            }
        }

        // work out the final result
        switch ($this->evaluationPolicy) {
        case self::EVALUATE_AND:
            $finalResult = true;
            foreach ($ruleResults as $result) {
                $finalResult = ($finalResult && $result->getValue());
            }
            break;
        case self::EVALUATE_OR:
            $finalResult = false;
            foreach ($ruleResults as $result) {
                if ($result->getValue()) {
                    $finalResult = true;
                    break;
                }
            }
            break;
        }

        return new Proposition($this->name, $finalResult);
    }

}
