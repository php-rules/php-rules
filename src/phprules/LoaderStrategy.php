<?php
namespace phprules;

/**
 * Loader strategy interface.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
interface LoaderStrategy
{
    /**
     * Set the associated rule.
     *
     * @param Rule $rule The rule.
     */
    public function setRule(Rule $rule);

    /**
     * Set the associated rule context.
     *
     * @param RuleContext $ruleContext The rule context.
     */
    public function setRuleContext(RuleContext $ruleContext);

    /**
     * Load rule.
     *
     * @param  mixed $resource The resource to load from.
     * @return Rule  The loaded rule.
     */
    public function loadRule($resource);

    /**
     * Load the rule context.
     *
     * @param  mixed       $resource The resource to load from.
     * @param  mixed       $args     Optional args.
     * @return RuleContext The loaded rule context.
     */
    public function loadRuleContext($resource, $args);

}
