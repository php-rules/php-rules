<?php
namespace phprules;

/**
 * A Rule is a constraint on the operation of business systems. They:
 *
 * <ol>
 *  <li>Constrain business strucuture.</li>
 *  <li>Constrain busines operations, i.e., they determine the sequence of actions in business workflows.</li>
 * </ol>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class Rule {
    /**
     * The name of the Rule.
     * @access public
     * @var string
     */
    private $name;
    /**
     * The RuleElements that comprise the Rule.
     * @access public
     * @var array
     * @see RuleElement
     */
    protected $elements;
    /**
     * A stack data structure used during Rule evaluation.
     * @access private
     * @var array
     */
    private $stack;


    /**
     * Create new rule.
     *
     * @param string name Optional rule name; default is an empty string.
     */
    public function __construct($name='') {
        $this->name = $name;
        $this->elements = array();
    }

    /**
     * Get the name.
     *
     * @return string The name of this rule.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Adds a Proposition to the array of {@link $elements}.
     *
     * @param string $name The proposition's statement.
     * @param boolean $truthValue Whether the Proposition is <code>true</code> or <code>false</code>.
     */
    public function addProposition($name, $truthValue) {
        $this->elements[] = new Proposition($name, $truthValue);
    }

    /**
     * Adds a Variable to the array of {@link $elements}.
     *
     * @param string $name The Variable's name.
     * @param mixed $value The Variable's value.
     */
    public function addVariable($name, $value) {
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
     * Evaluates a RuleContext.
     *
     * <p>The RuleContext contains Propositions and Variables that have  specific values.
     *  To apply the context, simply copy these values into the corresponding Propositions and Variables in the Rule.</p>
     *
     * @param RuleContext $ruleContext The context in which to evaluate this rule.
     * @return Proposition
     */
    public function evaluate(RuleContext $ruleContext) {
        // The context contains Propositions and Variables that have
        // specific values. To apply the context, simply copy these values
        // into the corresponding Propositions and Variables in the Rule
        $this->stack = array();
        foreach ($this->elements as $e) {
            if ($e instanceof Proposition || $e instanceof Variable) {
                $element = $ruleContext->findElement($e->getName());
                if ($element) {
                    $e->setValue($element->getValue());
                } else {
                    $e->setValue(false);
                }
            }
        }
        return $this->process();
    }

    /**
     * Processes RuleElements for RuleContext evaluation.
     *
     * @return RuleElement
     */
    private function process() {
        $this->stack = array();
        foreach ($this->elements as $e) {
            if ($e instanceof Operator) {
                $this->processOperator($e, $this->stack);
            } else if ($e instanceof Proposition) {
                $this->processProposition($e, $this->stack);
            } else if ($e instanceof Variable) {
                $this->processVariable($e, $this->stack);
            } else {
                throw new \Exception("Invalid element type: " . get_class($e));
            }
        }

        return array_pop($this->stack);
    }

    /**
     * Driver method for processing Operators for RuleContext evaluation.
     * @access private
     */
    private function processOperator( $operator ) {
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
