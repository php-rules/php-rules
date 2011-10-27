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
            if ($element instanceof Operator) {
                $this->processOperator($element);
            } else if ($element instanceof Proposition) {
                $this->processProposition($element);
            } else if ($element instanceof Variable) {
                $this->processVariable($element);
            } else {
                throw new \Exception("Invalid element type: " . get_class($element));
            }
        }

        return array_pop($this->stack);
    }

    /**
     * Driver method for processing Operators for RuleContext evaluation.
     * @access private
     */
    private function processOperator($operator) {
        if ($operator->getName() == Operator::LOGICAL_AND) {
            $this->processAnd();
        } else if ($operator->getName() == Operator::LOGICAL_OR) {
            $this->processOr();
        } else if ($operator->getName() == Operator::LOGICAL_XOR) {
            $this->processXor();
        } else if ($operator->getName() == Operator::LOGICAL_NOT) {
            $this->processNot();
        } else if ($operator->getName() == Operator::EQUAL_TO) {
            $this->processEqualTo();
        } else if ($operator->getName() == Operator::NOT_EQUAL_TO) {
            $this->processNotEqualTo();
        } else if ($operator->getName() == Operator::LESS_THAN) {
            $this->processLessThan();
        } else if ($operator->getName() == Operator::GREATER_THAN) {
            $this->processGreaterThan();
        } else if ($operator->getName() == Operator::LESS_THAN_OR_EQUAL_TO) {
            $this->processLessThanOrEqualTo();
        } else if ($operator->getName() == Operator::GREATER_THAN_OR_EQUAL_TO) {
            $this->processGreaterThanOrEqualTo();
        } else if ($operator->getName() == Operator::IN) {
            $this->processIn();
        }
    }

    /**
     * Processes Propositions for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processProposition($proposition) {
        $this->stack[] = $proposition;
    }

    /**
     * Processes Variables for RuleContext evaluation and adds them to the {@link $stack}.
     */
	  private function processVariable($variable) {
		   $this->stack[] = $variable;
	  }

    /**
     * Processes AND Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
	  private function processAnd() {
		   $rhs = array_pop($this->stack);
		   $lhs = array_pop($this->stack);
		   $this->stack[] = $rhs->logicalAnd($lhs);
	  }

    /**
     * Processes OR Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processOr() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->logicalOr($lhs);
    }

    /**
     * Processes XOR Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processXor() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->logicalXor($lhs);
    }

    /**
     * Processes NOT Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processNot() {
        $rhs = array_pop($this->stack);
        $this->stack[] = $rhs->logicalNot();
    }

    /**
     * Processes EQUALTO Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processEqualTo() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->equalTo($lhs);
    }

    /**
     * Processes NOTEQUALTO Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processNotEqualTo() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->notEqualTo($lhs);
    }

      /**
       * Processes LESSTHAN Operators for RuleContext evaluation and adds them to the {@link $stack}.
       */
    private function processLessThan() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->lessThan($lhs);
    }

    /**
     * Processes GREATERTHAN Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processGreaterThan() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->greaterThan($lhs);
    }

    /**
     * Processes LESSTHANOREQUALTO Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processLessThanOrEqualTo() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->lessThanOrEqualTo($lhs);
    }

    /**
     * Processes GREATERHANOREQUALTO Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processGreaterThanOrEqualTo() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->greaterThanOrEqualTo($lhs);
    }

    /**
     * Processes IN Operators for RuleContext evaluation and adds them to the {@link $stack}.
     */
    private function processIn() {
        $rhs = array_pop($this->stack);
        $lhs = array_pop($this->stack);
        $this->stack[] = $rhs->in($lhs);
    }

    /**
     * Returns an infixed, readable representation of the Rule.
     *
     * @return string
     */
    public function __toString() {
        $result = $this->name . "\n";
        foreach ($this->elements as $e) {
            $result = $result . $e . "\n";
        }

        return $result;
    }

}
