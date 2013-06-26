<?php
namespace phprules;

use InvalidArgumentException;
use phprules\source\SourceInterface;

/**
 * Agnostic rule loader.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
class RuleLoader
{
    /**
     * Get statements from the given source.
     *
     * <p>A statement is considered a single line of either a rule or context.</p>
     *
     * @param  SourceInterface $source The source to load from.
     * @return array List of tokens.
     */
    protected function getStatements(SourceInterface $source) {
        return $this->tokenize($source->getData());
    }

    /**
     * Tokenize loaded rule data into separate statements.
     *
     * <p>This default implementation will tokenize on CR/LF and also ';'</p>
     *
     * <p>Statements starting with "#' will be treated as comment.</p>
     *
     * @param string $data The data to tokenize.
     * @return array The tokenized data.
     */
    protected function tokenize($data)
    {
        $tokens = array();
        foreach (preg_split('/[\r\n;]+/', $data) as $line) {
            $line = trim($line);
            if (!empty($line) && '#' != $line[0]) {
                $tokens[] = explode(' ', $line);
            }
        }

        return $tokens;
    }

    /**
     * Load a rule.
     *
     * @param SourceInterface $source The source to load from.
     * @param RuleInterface $rule Optional rule to load into; default is <code>null</code> to create a new one.
     * @return Rule The loaded rule.
     * @throws InvalidArgumentException If the statement is not valid.
     */
    public function loadRule(SourceInterface $source, RuleInterface $rule = null)
    {
        $rule = $rule ?: new Rule(spl_object_hash($this).time());

        $statements = $this->getStatements($source);
        foreach ($statements as $tokens) {
            switch (count($tokens)) {
            case 1: // operator
                $this->processOperator($tokens, $rule);
                break;
            case 2: // var, bool
            case 3: // statement
                $this->processRuleStatement($tokens, $rule);
                break;
            default:
                throw new InvalidArgumentException('Invalid statement');
            }
        }

        return $rule;
    }

    /**
     * Process operator statement.
     *
     * @param array $tokens The statement token.
     * @param RuleInterface $rule Rule for this operator.
     */
    protected function processOperator($tokens, RuleInterface $rule)
    {
        $rule->addOperator($tokens[0]);
    }

    /**
     * Process a rule statement.
     *
     * @param array $tokens The statement token.
     * @param RuleInterface $rule Rule for this statement.
     */
    protected function processRuleStatement($tokens, RuleInterface $rule)
    {
        // since we are processing a rule, the actual values are not relevant
        if ('IS' == $tokens[1]) {
            $rule->addProposition($tokens[0]);
        } elseif ('BOOL' == $tokens[0]) {
            $rule->addProposition($tokens[1]);
        } elseif ('EQUALS' == $tokens[1]) {
            $rule->addVariable($tokens[0]);
        } elseif ('VAR' == $tokens[0]) {
            $rule->addVariable($tokens[1]);
        } elseif ('CONST' == $tokens[0]) {
            // TODO: strip optional quotes
            $rule->addVariable(null, $tokens[1]);
        } else if ($tokens) {
            throw new InvalidArgumentException('Invalid token');
        }
    }

    /**
     * Load a rule context.
     *
     * @param  SourceInterface $source The source to load from.
     * @param  RuleContext $ruleContext  Optional rule context to load into; default is <code>null</code>.
     * @return RuleContext The loaded rule context.
     */
    public function loadRuleContext(SourceInterface $source, RuleContext $ruleContext = null)
    {
        $ruleContext = $ruleContext ?: new RuleContext();

        $statements = $this->getStatements($source);
        foreach ($statements as $tokens) {
            if (count($tokens) == 3) {
                $this->processRuleContextStatement($tokens, $ruleContext);
            }
        }

        return $ruleContext;
    }

    /**
     * Process a rule context statement.
     *
     * @param array $tokens The tokens.
     * @param  RuleContext $ruleContext The rule context for this statement.
     * @throws InvalidArgumentException If an invalid token is found.
     */
    protected function processRuleContextStatement($tokens, $ruleContext)
    {
        if ('EQUALS' == $tokens[1]) {
            $ruleContext->addElement($tokens[0], $tokens[2]);
        } elseif ('IS' == $tokens[1]) {
            $ruleContext->addElement($tokens[0], $tokens[2] == 'true');
        } else if ($tokens) {
            throw new InvalidArgumentException('Invalid token');
        }
    }

}
