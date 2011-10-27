<?php
namespace phprules;

/**
 * Represents an element of a Rule or RuleContext.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
abstract class RuleElement {

    /**
     * The name of the RuleElement.
     *
     * @var string
     */
    private $name;


    /**
     * Create new element.
     *
     * @param string name The name.
     */
    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * Get the name of this element.
     *
     * @return string The name.
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Return a string representation of this rule element.
     *
     * @return string
     */
    public function __toString() {
        return '['.get_class($this).' name='.$this->name.']';
    }

}
