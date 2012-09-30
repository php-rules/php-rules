<?php
namespace phprules\strategy;

use phprules\Rule;
use phprules\RuleContext;
use phprules\LoaderStrategy;

/**
 * Abstract base loader strategy.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
abstract class AbstractLoaderStrategy implements LoaderStrategy
{
    protected $rule;
    protected $ruleContext;

    /**
     * Create new instance.
     */
    public function __construct()
    {
        $this->rule = null;
        $this->ruleContext = null;
    }

    /**
     * Set the associated rule.
     *
     * @param Rule $rule The rule.
     */
    public function setRule(Rule $rule)
    {
        $this->rule = $rule;
    }

    /**
     * Set the associated rule context.
     *
     * @param RuleContext $ruleContext The rule context.
     */
    public function setRuleContext(RuleContext $ruleContext)
    {
        $this->ruleContext = $ruleContext;
    }

    /**
     * Get a rule element value.
     *
     * @param  array $tokens The tokens.
     * @param  mixed $args   Optional args.
     * @return mixed The value.
     */
    protected function getRuleElementValue($tokens, $args)
    {
        return $tokens[2];
    }

    /**
     * Process a rule context statement.
     *
     * @param array $tokens The tokens.
     * @param mixed $args   Optional args.
     */
    protected function processRuleContextStatement($tokens, $args)
    {
        if ('EQUALS' == $tokens[1]) {
            // It's a Variable
            $this->ruleContext->addVariable($tokens[0], $tokens[2]);
        } elseif ('IS' == $tokens[1]) {
            // It's a Proposition
            $this->ruleContext->addProposition($tokens[0], $tokens[2]);
        }
    }

    /**
     * Get statements from the given resource.
     *
     * @param  mixed $resource The resource to load from.
     * @return array List of tokens.
     */
    protected abstract function getStatements($resource);

    /**
     * Load rule.
     *
     * @param  mixed $resource The resource to load from.
     * @return Rule  The loaded rule.
     */
    public function loadRule($resource)
    {
        $OPERATOR = 1;
        $STATEMENT = 3;
        $statements = $this->getStatements($resource);
        foreach ($statements as $tokens) {
            switch (count($tokens)) {
            case 1:
                $this->processOperator($tokens, $this->rule);
                break;
            case 3:
                $this->processRuleStatement($tokens, $this->rule);
                break;
            }
        }

        return $this->rule;
    }

    /**
     * Load the rule context.
     *
     * @param  mixed       $resource The resource to load from.
     * @param  mixed       $args     Optional args.
     * @return RuleContext The loaded rule context.
     */
    public function loadRuleContext($resource, $args)
    {
        $statements = $this->getStatements($resource);
        foreach ($statements as $tokens) {
            if (count($tokens) == 3) {
                $this->processRuleContextStatement($tokens, $args);
            }
        }

        return $this->ruleContext;
    }

    /**
     * Process a rule statement.
     *
     * @param array $tokens            The statement token.
     * @param mixed $ruleOrRuleContext Rule or context for this statement.
     */
    protected function processRuleStatement($tokens, $ruleOrRuleContext)
    {
        if ('IS' == $tokens[1]) {
            $ruleOrRuleContext->addProposition($tokens[0], (bool) $tokens[1]);
        } elseif ('EQUALS' == $tokens[1]) {
            $ruleOrRuleContext->addVariable($tokens[0], $tokens[2]);
        }
    }

    /**
     * Process operator statement.
     *
     * @param array $tokens            The statement token.
     * @param mixed $ruleOrRuleContext Rule or context for this statement.
     */
    protected function processOperator($tokens, $ruleOrRuleContext)
    {
        $ruleOrRuleContext->addOperator($tokens[0]);
    }

}
