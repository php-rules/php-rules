<?php
namespace phprules;

/**
 * A rule.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class Rule extends AbstractRule
{
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
    public function __construct($name)
    {
        parent::__construct($name);
        $this->elements = array();
    }

    /**
     * Adds a proposition.
     *
     * @param mixed   $proposition The propositions statement or a <code>Proposition</code>.
     * @param boolean $result      Optional proposition result; default is <code>false</code> - ignored if <code>$name</code>
     *  is a <code>Proposition</code>.
     */
    public function addProposition($proposition, $result = false)
    {
        if ($proposition instanceof Proposition) {
            $this->elements[] = $proposition;
        } else {
            $this->elements[] = new Proposition($proposition, $result);
        }
    }

    /**
     * Adds a variable.
     *
     * @param mixed $variable The name or a <code>Variable</code> instance.
     * @param mixed $value    The value; default is <code>null</code> - ignored if <code>$name</code>
     *  is a <code>Variable</code>.
     */
    public function addVariable($variable, $value = null)
    {
        if ($variable instanceof Variable) {
            $this->elements[] = $variable;
        } else {
            $this->elements[] = new Variable($variable, $value);
        }
    }

    /**
     * Adds an Operator to the array of {@link $elements}.
     *
     * @param string $operator The Boolean or quantifier operator.
     */
    public function addOperator($operator)
    {
        $this->elements[] = new Operator($operator);
    }

    /**
     * Get all elements of this rule.
     *
     * @return array List of <code>RuleElement</code> instances.
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * {@inheritDoc}
     */
    public function evaluate(RuleContext $ruleContext = null)
    {
        $this->stack = array();
        if ($ruleContext) {
            foreach ($this->elements as $name => $element) {
                if ($element instanceof Proposition || $element instanceof Variable) {
                    $element->applyRuleContext($ruleContext);
                }
            }
        }

        return $this->evaluateElements();
    }

    /**
     * Evaluate all elements.
     *
     * @return RuleElement The final result.
     */
    private function evaluateElements()
    {
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
    public function __toString()
    {
        $result = $this->name . "\n";
        foreach ($this->elements as $element) {
            $result .= $element . "\n";
        }

        return $result;
    }

}
