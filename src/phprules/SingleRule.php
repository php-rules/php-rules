<?php
namespace phprules;

/**
 * A single rule.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class SingleRule extends AbstractRule {
    /**
     * The RuleElements that comprise the Rule.
     *
     * @var array
     * @see RuleElement
     */
    protected $elements;
    /**
     * A stack data structure used during Rule evaluation.
     *
     * @var array
     */
    private $stack;


    /**
     * Create new instance.
     *
     * @param string $name The rule name.
     */
    public function __construct($name) {
        parent::__construct($name);
        $this->elements = array();
    }

    /**
     * Adds a proposition.
     *
     * @param string $name The propositions statement.
     * @param boolean $result Optional proposition result; default is <code>false</code>.
     */
    public function addProposition($name, $result=false) {
        $this->elements[] = new Proposition($name, $result);
    }

    /**
     * Adds a variable.
     *
     * @param string $name The name.
     * @param mixed $value The value; default is <code>null</code>.
     */
    public function addVariable($name, $value=null) {
        $this->elements[] = new Variable($name, $value);
    }

    /**
     * Adds an Operator to the array of {@link $elements}.
     *
     * @param string $operator The Boolean or quantifier operator.
     */
    public function addOperator($operator) {
        $this->elements[] = new Operator($operator);
    }

    /**
     * Get all elements.
     *
     * @return array List of <code>RuleElement</code> instances.
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(RuleContext $ruleContext) {
        $this->stack = array();
        $this->applyRuleContext($ruleContext);
        return $this->evaluateElements();
    }

    /**
     * Apply rule context to all elements.
     *
     * @param RuleContext $ruleContext The context in which to evaluate this rule.
     */
    protected function applyRuleContext(RuleContext $ruleContext) {
        foreach ($this->elements as $element) {
            // TODO: move into RuleElement
            if ($element instanceof Proposition || $element instanceof Variable) {
                $contextElement = $ruleContext->findElement($element->getName());
                if ($contextElement) {
                    $element->setValue($contextElement->getValue());
                } else {
                    throw new \Exception('Incomplete rule context, missing: ' . $element->getName());
                }
            }
        }
    }

    /**
     * Evaluate all elements.
     *
     * @return RuleElement The final result.
     */
    private function evaluateElements() {
        foreach ($this->elements as $element) {
            $this->stack = $element->evaluate($this->stack);
        }

        return array_pop($this->stack);
    }

    /**
     * Returns an infixed, readable representation of the Rule.
     *
     * @return string
     */
    public function __toString() {
        $result = $this->name . "\n";
        foreach ($this->elements as $element) {
            $result .= $element . "\n";
        }

        return $result;
    }

}
