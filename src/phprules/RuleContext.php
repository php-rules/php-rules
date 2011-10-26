<?php
namespace phprules;

/**
 * Contains the informational context for the execution of a Rule.
 *
 * <p>It represents this information as a collection of RuleElements that may be Propositions or
 *  Variables but not Operators.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class RuleContext {
    public $name;
    public $elements;

    /**
     * Create new context.
     *
     * @param string $name The context name.
     */
    public function __construct($name='') {
        $this->name = $name;
        // elements is a dictionary - a set of {name, value} pairs
        // The names are Proposition or Variable names and
        // the values are the Propositions or Variables themselves
        $this->elements = array();
    }

    /**
     * Adds a Proposition to the array of {@link $elements}.
     *
     * @param string $statement The Proposition's statement.
     * @param boolean $value Whether the Proposition is TRUE or FALSE.
     */
    public function addProposition($statement, $value) {
        $this->elements[$statement] = new Proposition($statement, $value);
    }

    /**
     * Adds a Variable to the array of {@link $elements}.
     *
     * @param string $name The Variable's name.
     * @param mixed $value The Variable's value.
     */
    public function addVariable($name, $value) {
        $this->elements[ $name ] = new Variable($name, $value);
    }

    /**
     * Find and return a RuleElement by name, if it exists.
     *
     * @param string $name The name (i.e., "key") of the RuleElement.
     * @return RuleElement
     */
    public function findElement($name) {
      return $this->elements[ $name ];
    }

    /**
     * Append the given rule context.
     *
     * @paaram RuleContext $ruleContext The context to append.
     */
    public function append($ruleContext) {
        foreach ($ruleContext->elements as $e) {
          $this->elements[ $e->getName() ] = $e;
        }
    }

    /**
     * Returns an infixed, readable representation of the RuleContext.
     *
     * @return string
     */
    public function __toString() {
        $result = "";
        foreach (array_values($this->elements) as $e) {
            $result = $result . $e . "\n";
        }

        return $result;
    }

}
