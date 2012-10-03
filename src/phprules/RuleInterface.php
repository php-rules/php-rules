<?php
namespace phprules;

/**
 * A Rule is a constraint on the operation of business systems.
 *
 * <p>They:</p>
 * <ol>
 *  <li>Constrain business strucuture.</li>
 *  <li>Constrain busines operations, i.e., they determine the sequence of actions in business workflows.</li>
 * </ol>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
interface RuleInterface
{
    /**
     * Get the name of this rule.
     *
     * @return string The unique rule name.
     */
    public function getName();

    /**
     * Evaluate this rule within the given context.
     *
     * @param  RuleContext $ruleContext The context in which to evaluate this rule.
     * @return Proposition
     */
    public function evaluate(RuleContext $ruleContext);

}
