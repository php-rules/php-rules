<?php
namespace phprules;

/**
 * Represents an element of a Rule or RuleContext.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
abstract class RuleElement
{
    /**
     * The name of the RuleElement.
     *
     * @var string
     */
    protected $name;

    /**
     * Create new element.
     *
     * @param string name The name.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Get the name of this element.
     *
     * @return string The name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Evalute this rule element.
     *
     * <p>In the context of a <code>Rule</code> this means returning something to be pushed to the
     *  processing stack.</p>
     * <p>The default behaviour is to return a reference to the this rule element - <code>$this</code>.</p>
     *
     * @param  array $stack The stack.
     * @return array New updated stack.
     */
    public function evaluate($stack)
    {
        $stack[] = $this;

        return $stack;
    }

    /**
     * Return a string representation of this rule element.
     *
     * @return string
     */
    public function __toString()
    {
        return '['.get_class($this).' name='.$this->name.']';
    }

}
