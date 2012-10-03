<?php
namespace phprules;

/**
 * Loader strategy.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
interface LoaderStrategyInterface
{
    /**
     * Set the associated rule.
     *
     * @param RuleInterface $rule The rule.
     */
    public function setRule(RuleInterface $rule);

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
     * @return RuleInterface The loaded rule.
     */
    public function loadRule($resource);

    /**
     * Load the rule context.
     *
     * @param  mixed       $resource The resource to load from.
     * @param  mixed       $args     Optional args; default is <code>null</code>.
     * @return RuleContext The loaded rule context.
     */
    public function loadRuleContext($resource, $args = null);

}
