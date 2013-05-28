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
     * Parse the given rule string.
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

        /*

( ( C == 'foo' ) AND ( ( B == 'bar' ) OR A ) )


BOOL A
VAR B
CONST bar
EQUALTO
OR
VAR C
CONST foo
EQUALTO
AND
        */

}
