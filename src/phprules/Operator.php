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
    const IN = 'IN';
    const NOT_IN = 'NOT_IN';

    private static $OPERATORS = array(
        self::LOGICAL_OR,
        self::LOGICAL_AND,
        self::LOGICAL_NOT,
        self::LOGICAL_XOR,
        self::EQUAL_TO,
        self::NOT_EQUAL_TO,
        self::LESS_THAN,
        self::LESS_THAN_OR_EQUAL_TO,
        self::GREATER_THAN,
        self::GREATER_THAN_OR_EQUAL_TO,
        self::IN,
        self::NOT_IN
    );


    /**
     * Create new instance.
     *
     * @param string operator The operator.
     */
    public function __construct($operator) {
        parent::__construct($operator);
        if (!in_array($operator, self::$OPERATORS)) {
            throw new \Exception($operator . " is not a valid operator.");
        }
    }


    /**
     * {@inheritDoc}
     */
    public function evaluate($stack) {
        switch ($this->getName()) {
        case self::LOGICAL_OR:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->logicalOr($lhs);
            break;
        case self::LOGICAL_AND:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->logicalAnd($lhs);
            break;
        case self::LOGICAL_NOT:
            $rhs = array_pop($stack);
            $stack[] = $rhs->logicalNot();
            break;
        case self::LOGICAL_XOR:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->logicalXor($lhs);
            break;
        case self::EQUAL_TO:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->equalTo($lhs);
            break;
        case self::NOT_EQUAL_TO:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->notEqualTo($lhs);
            break;
        case self::LESS_THAN:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->lessThan($lhs);
            break;
        case self::LESS_THAN_OR_EQUAL_TO:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->lessThanOrEqualTo($lhs);
            break;
        case self::GREATER_THAN:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->greaterThan($lhs);
            break;
        case self::GREATER_THAN_OR_EQUAL_TO:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->greaterThanOrEqualTo($lhs);
            break;
        case self::IN:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->in($lhs);
            break;
        case self::NOT_IN:
            $rhs = array_pop($stack);
            $lhs = array_pop($stack);
            $stack[] = $rhs->NotIn($lhs);
            break;
        }

        return $stack;
    }

}
