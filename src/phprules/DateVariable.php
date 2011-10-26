<?php
namespace phprules;

/**
 * A symbol that represents Date values.
 *
 * <p>DateVariable encapulates {@link http://us2.php.net/manual/en/function.strtotime.php strtotime}, which parses any English textual datetime description into a Unix timestamp.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class DateVariable extends Variable {

    /**
     * Constructor initializes {@link $name}, and the {@link $value}.
     *
     * @param string $name The name of the DateVariable
     * @param string $value A date/time string in a {@link http://us2.php.net/manual/en/datetime.formats.php valid Date and Time format}.
     */
    public function __construct($name, $value) {
        parent::__construct($name, strtotime($value));
    }

    /**
     * Returns a human-readable statement and value.
     * @access public
     * @return string
     */
    public function __toString() {
        return "[DateVariable name=" . $this->name . ", value=" . $this->value . "]";
    }

}
