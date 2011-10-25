<?php
namespace phprules;

/**
 * Represents a Boolean operator or a quantifier operator.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class Operator extends RuleElement {
    const LOGICAL_OR               = 'OR';
    const LOGICAL_AND              = 'AND';
    const LOGICAL_NOT              = 'NOT';
    const LOGICAL_XOR              = 'XOR';
    const EQUAL_TO                 = 'EQUALTO';
    const NOT_EQUAL_TO             = 'NOTEQUALTO';
    const LESS_THAN                = 'LESSTHAN';
    const LESS_THAN_OR_EQUAL_TO    = 'LESSTHANOREQUALTO';
    const GREATER_THAN             = 'GREATERTHAN';
    const GREATER_THAN_OR_EQUAL_TO = 'GREATERTHANOREQUALTO';

    /**
     * The name of the RuleElement.
     *
     * @var array
     */
    private $operators;


    /**
     * Constructor initializes {@link $name}, i.e., the operator.
     * @access public
     */
    public function __construct($operator) {
        $this->operators = array( "AND", "OR", "NOT", "XOR", "EQUALTO", "NOTEQUALTO", "LESSTHAN", "GREATERTHAN", "LESSTHANOREQUALTO", "GREATERTHANOREQUALTO" );
        if (in_array($operator, $this->operators)) {
            parent::__construct($operator);
        } else {
            throw new \Exception($operator . " is not a valid operator.");
        }
    }

    /**
     * Returns "Operator."
     *
     * @return string
     */
    public function getType() {
        return "Operator";
    }

}