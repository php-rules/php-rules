<?php
namespace phprules;

/**
 * Loads a Rule from a file.
 *
 * <p><code>RuleLoader</code> uses the strategy pattern so you define custom algorithms for loading <code>Rule</code>s and <code>RuleContexts</code>.</p>
 *
 * @author Greg Swindle <greg@swindle.net>
 * @package phprules
 */
class RuleLoader {
    /**
     * The algorithm used to retrieve a Rule or RuleContext.
     *
     * @var LoaderStrategy
     */
    private $loaderStrategy;
    /**
     * @access public
     * @var Rule
     */
    public $rule;
    /**
     * @access public
     * @var RuleContext
     */
    public $ruleContext;


    /**
     * Create a new context.
     */
    public function __construct() {
        $this->rule = new SingleRule(spl_object_hash($this).time());
        $this->ruleContext = new RuleContext();
        $this->loaderStrategy = null;
    }

    /**
     * Set the loader strategy to be used.
     *
     * @param LoaderStrategy $loaderStrategy The loader strategy.
     */
    public function setStrategy(LoaderStrategy $loaderStrategy) {
        $this->loaderStrategy = $loaderStrategy;
        $loaderStrategy->setRule($this->rule);
        $loaderStrategy->setRuleContext($this->ruleContext);
    }

    /**
     * Load rule.
     *
     * @param mixed $resource The resource to load from.
     * @return Rule The loaded rule.
     */
    public function loadRule($resource) {
        return $this->loaderStrategy->loadRule($resource);
    }

    /**
     * Load the rule context.
     *
     * @param mixed $resource The resource to load from.
     * @param mixed $args Optional args.
     * @return RuleContext The loaded rule context.
     */
    public function loadRuleContext($resource, $args) {
        return $this->loaderStrategy->loadRuleContext($resource, $args);
    }

}
