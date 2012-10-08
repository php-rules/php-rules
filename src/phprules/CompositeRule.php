<?php
namespace phprules;

use phprules\operators\Logical;

/**
 * A rule constisting of multiple rules.
 *
 * <p>The individual results of the containing rules are evaluated to a single result based on the given
 * evaluation policy.</p>
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class CompositeRule extends AbstractRule
{
    private $rules;
    private $operator;

    /**
     * Create new composite rule.
     *
     * @param string $name     The name.
     * @param string $operator The operator; default is <code>Logical::LOGICAL_AND</code>.
     */
    public function __construct($name, $operator = Logical::LOGICAL_AND)
    {
        parent::__construct($name);
        $this->name = $name;
        $this->operator = $operator;
        $this->rules = array();
    }

    /**
     * Add rule.
     *
     * @param Rule $rule The rule to add.
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(RuleContext $ruleContext = null)
    {
        $ruleResults = array();
        // accumulate the results of evaluating the Rules
        foreach ($this->rules as $rule) {
            $result = $rule->evaluate($ruleContext);
            $ruleResults[$rule->getName()] = $result;
        }

        // work out the final result
        $nop = null;
        switch ($this->operator) {
        case Logical::LOGICAL_AND:
            $finalResult = true;
            foreach ($ruleResults as $result) {
                $finalResult = ($finalResult and $result->getValue());
            }
            break;
        case Logical::LOGICAL_OR:
            $finalResult = false;
            foreach ($ruleResults as $result) {
                if ($result->getValue()) {
                    $finalResult = true;
                    break;
                }
            }
            break;
        case Logical::LOGICAL_XOR:
            $finalResult = false;
            foreach ($ruleResults as $result) {
                if ($result->getValue()) {
                    if ($finalResult) {
                        $finalResult = false;
                        break;
                    }
                    $finalResult = true;
                }
            }
            break;
        }

        // prepare name
        $names = array();
        array_walk($ruleResults, function ($item, $key) use (&$names) { $names[] = $item->getName(); });
        $name = sprintf('( %s )', implode(sprintf(' %s ', $this->operator), $names));

        return new Proposition($name, $finalResult);
    }

}
