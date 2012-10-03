<?php
namespace phprules\strategy;

use InvalidArgumentException;
use phprules\RuleInterface;
use phprules\RuleContext;
use phprules\LoaderStrategyInterface;

/**
 * Abstract base loader strategy.
 *
 * @author Greg Swindle <greg@swindle.net>
 * @author Martin Rademacher <mano@radebatz.net>
 */
abstract class AbstractLoaderStrategy implements LoaderStrategyInterface
{
    protected $rule;
    protected $ruleContext;

    /**
     * Create new instance.
     */
    public function __construct()
    {
        $this->rule = null;
        $this->ruleContext = null;
    }

    /**
     * {@inheritDoc}
     */
    public function setRule(RuleInterface $rule)
    {
        $this->rule = $rule;
    }

    /**
     * {@inheritDoc}
     */
    public function setRuleContext(RuleContext $ruleContext)
    {
        $this->ruleContext = $ruleContext;
    }

    /**
     * Get a rule element value.
     *
     * @param  array $tokens The tokens.
     * @param  mixed $args   Optional args.
     * @return mixed The value.
     */
    protected function getRuleElementValue($tokens, $args)
    {
        return $tokens[2];
    }

    /**
     * Get statements from the given resource.
     *
     * @param  mixed $resource The resource to load from.
     * @return array List of tokens.
     */
    protected abstract function getStatements($resource);

    /**
     * {@inheritDoc}
     */
    public function loadRuleContext($resource, $args = null)
    {
        $statements = $this->getStatements($resource);
        foreach ($statements as $tokens) {
            if (count($tokens) == 3) {
                $this->processRuleContextStatement($tokens, $args);
            }
        }
        return $this->ruleContext;
    }

    /**
     * Process a rule context statement.
     *
     * @param array $tokens The tokens.
     * @param mixed $args   Optional args; default is <code>null</code>.
     */
    protected function processRuleContextStatement($tokens, $args = null)
    {
        if ('EQUALS' == $tokens[1]) {
            $this->ruleContext->addVariable($tokens[0], $tokens[2]);
        } elseif ('IS' == $tokens[1]) {
            $this->ruleContext->addProposition($tokens[0], $tokens[2] == 'true');
        } else if ($tokens) {
            throw new InvalidArgumentException('Invalid token');
        }
    }

    /**
     * {@inheritDoc}
     */
    public function loadRule($resource)
    {
        $statements = $this->getStatements($resource);
        foreach ($statements as $tokens) {
            switch (count($tokens)) {
            case 1: // operator
                $this->processOperator($tokens, $this->rule);
                break;
            case 2: // var, bool
            case 3: // statement
                $this->processRuleStatement($tokens, $this->rule);
                break;
            default:
                throw new InvalidArgumentException('Invalid statement');
            }
        }

        return $this->rule;
    }

    /**
     * Process operator statement.
     *
     * @param array $tokens The statement token.
     * @param RuleInterface $rule Rule for this statement.
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
            $rule->addVariable(null, $tokens[1]);
        } else if ($tokens) {
            throw new InvalidArgumentException('Invalid token');
        }
    }

}
