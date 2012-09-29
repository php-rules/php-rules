<?php
namespace phprules;

/**
 * An ActivityRule represents a type of Rule that automatically executes an
 * activity when it evaluates to <code>true</code>.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
abstract class ActivityRule extends SingleRule
{
    /**
     * Create new instance.
     *
     * @param string $name The rule name.
     */
    public function __construct($name)
    {
        parent::__construct($name);
    }

    /**
     * Execute this activity rule.
     */
    abstract public function execute();

    /**
     * {@inheritDoc}
     *
     * <p>If the result of evaluation is <code>true</code>, then the activity is executed.</p>
     */
    public function evaluate(RuleContext $ruleContext)
    {
        $result = parent::evaluate($ruleContext);

        if ($result->getValue()) {
            $this->execute();
        }

        return $result;
      }

}
