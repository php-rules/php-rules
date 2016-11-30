<?php
namespace phprules;

/**
 * Contains the informational context for the execution of a Rule.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class RuleContext
{
    private $elements = array();
    private $name;

    /**
     * Create new context.
     *
     * @param array  $elements The elements; default is an empty array.
     * @param string $name     Optional context name; default is an empty string.
     */
    public function __construct($elements = array(), $name = '')
    {
        if(is_array($elements)) {
            $this->elements = $elements;    
        }
        
        $this->name = $name;
    }

    /**
     * Adds an element.
     *
     * @param string $name  The element's name.
     * @param mixed  $value The element's value.
     */
    public function addElement($name, $value)
    {
        $this->elements[$name] = $value;
    }

    /**
     * Get all element.
     *
     * @param array All elements.
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Get an element value.
     *
     * @param  string $name The name (i.e., "key") of the element.
     * @return mixed  The value or <code>null</code>.
     */
    public function getElement($name)
    {
        return array_key_exists($name, $this->elements) ? $this->elements[$name] : null;
    }

    /**
     * Test if an element for the given name exits.
     *
     * @param  string  $name The name (i.e., "key") of the element.
     * @return boolean <code>true</code> if it exits, <code>false</code> otherwise.
     */
    public function hasElement($name)
    {
        return array_key_exists($name, $this->elements);
    }

    /**
     * Append the given rule context.
     *
     * @paaram RuleContext $ruleContext The context to append.
     */
    public function append(RuleContext $ruleContext)
    {
        foreach ($ruleContext->elements as $name => $value) {
            $this->elements[$name] = $value;
        }
    }

    /**
     * Returns a human-readable statement and value.
     *
     * @return string
     */
    public function __toString()
    {
        $elements = array();
        foreach ($this->elements as $name => $value) {
            $elements[] = sprintf('%s=%s', $name, is_bool($value) ? ($value ? 'true' : 'false') : $value);
        }

        return "[".get_class($this).($this->name ? " name=".$this->name : '')." elements=[" . implode(', ', $elements) . "]]";
    }

}
