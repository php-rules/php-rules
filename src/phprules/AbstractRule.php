<?php
namespace phprules;

/**
 * Abstract <code>Rule</code> ase class.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
abstract class AbstractRule implements Rule
{
    protected $name;

    /**
     * Create new instance.
     *
     * @param string $name The rule name.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Return a string representation of this rule.
     *
     * @return string
     */
    public function __toString()
    {
        return '['.get_class($this).' name='.$this->name.']';
    }

}
