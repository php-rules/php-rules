<?php
namespace phprules;

/**
 * An ActivityRule represents a type of Rule that automatically executes an
 * activity when it evaluates to TRUE.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
abstract class ActivityRule extends Rule {

    /**
     * Execute this activity rule.
     */
    public abstract function execute();

    /**
     * Evaluates a RuleContext.
     *
     * <p>The RuleContext contains Propositions and Variables that have specific values.
     *  To apply the context, simply copy these values into the corresponding <code>Propositions</code> and <code>Variables</code> in the Rule.
     *  If the result of evaluation is <code>true</code>, then the activity is executed.</p>
     *
     * @param RuleContext $ruleContext
     * @return Proposition
     */
    public function evaluate( $ruleContext ) {
        // The context contains Propositions and Variables that have
        // specific values. To apply the context, simply copy these values
        // into the corresponding Propositions and Variables in the Rule
        $this->stack = array();
        foreach ($this->elements as $e) {
            if ($e->getType() == "Proposition" || $e->getType() == "Variable") {
                $element = $ruleContext->findElement($e->name);
                if ($element) {
                    $e->value = $element->value;
                } else {
                    $e->value = null;
                }
            }
        }
        $proposition = $this->process();
        if ($proposition->value){
            $this->execute();
        }
        return $proposition;
	  }

}
