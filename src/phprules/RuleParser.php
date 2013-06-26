<?php
namespace phprules;

use InvalidArgumentException;

/**
 * Rule parser/tokenizer.
 *
 * @author Martin Rademacher <mano@radebatz.net>
 */
class RuleParser
{
    protected static $OPERATORS = array('<', '>', '=');
    protected static $BRACKETS = array('(', ')');
    protected $ruleString;
    protected $pos;

    /**
     * Create new instance for the given rule string.
     *
     * @param string $ruleString Optional rule as string; default is <code>null</code>.
     */
    public function __construct($ruleString = null)
    {
        $this->ruleString = $ruleString;
        $this->pos = 0;
    }

    /**
     * Get the next token.
     *
     * @param boolean $peek Optional flag to peek only; default is <code>false</code>.
     * @return string The next token or <code>null</code>.
     */
    public function next($peek = false)
    {
        $token = null;
        $ii = $this->pos;
        while ($ii < strlen($this->ruleString)) {
            $char = $this->ruleString[$ii++];
            if (!empty($token)) {
                // context switch?
                if (in_array($char, static::$OPERATORS) && !in_array($token[0], static::$OPERATORS)) {
                    --$ii;
                    break;
                }
                if (!in_array($char, static::$OPERATORS))
                    if (in_array($char, static::$BRACKETS) || in_array($token[0], static::$OPERATORS)) {
                    --$ii;
                    break;
                }
            }
            if (' ' == $char || empty($char)) {
                if (empty($token)) {
                    continue;
                }
                break;
            }
            $token .= $char;
            if (in_array($char, static::$BRACKETS)) {
                break;
            }
        }

        if (!$peek) {
            $this->pos = $ii;
        }

        return $token;
    }

    /**
     * Parse the given rule string into separate token.
     *
     * @param string $ruleString The rule as string.
     * @return array A list of individual token.
     */
    public function parseToken($ruleString)
    {
        $this->ruleString = $ruleString ?: $this->ruleString;
        $this->pos = 0;

        if (!$this->ruleString) {
            new InvalidArgumentException('Need a rule string');
        }

        $token = array();
        while ($nt = $this->next()) {
            $token[] = $nt;
        }
        
        return $token;
    }

    /**
     * Parse the given rule string into a rule.
     *
     * @param string $ruleString The rule as string.
     * @return Rule A rule instance.
     */
    public function parseRule($ruleString)
    {
        $rule = new Rule(spl_object_hash($this).time());
        $stack = array();
        foreach ($this->parseToken($ruleString) as $token) {
            if ('(' == $token) {
                array_push($stack, array());
            } else if (')' == $token) {
                $elements = array_pop($stack);
                if (3 == count($elements)) {
                    $rule->addVariable($elements[0]);
                    $rule->addVariable($elements[2]);
                    $rule->addOperator($elements[1]);
                } else if (2 == count($elements)) {
                    $rule->addVariable($elements[1]);
                    $rule->addOperator($elements[0]);
                } else if (1 == count($elements)) {
                    $rule->addOperator($elements[0]);
                }
            } else {
                $tmp = array_pop($stack);
                $tmp[] = $token;
                array_push($stack, $tmp);
            }
        }

        return $rule;
    }

}
