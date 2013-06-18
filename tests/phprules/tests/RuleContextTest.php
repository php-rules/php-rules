<?php
namespace phprules\tests;

use Exception;
use phprules\Rule;
use phprules\RuleContext;
use phprules\operators\Comparison;

class RuleContextTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test incomplete rule context.
     */
    public function testIncompleteRuleContext()
    {
        $rule = new Rule('foo');
        $rule->addVariable('var1');
        $rule->addVariable('var2');
        $rule->addOperator(Comparison::EQUAL_TO);

        // the actual values...
        $ruleContext = new RuleContext();
        $ruleContext->addElement('var1', 'foo');

        try {
            $proposition = $rule->evaluate($ruleContext);
            $this->fail('expected incomplete context exception');
        } catch (Exception $e) {
            $this->assertEquals('incomplete context, missing value for: var2', $e->getMessage());
        }

        $ruleContext->addElement('var2', 'bar');
        // try again
        try {
            $proposition = $rule->evaluate($ruleContext);
            $this->assertFalse($proposition->value);
        } catch (Exception $e) {
            $this->fail('unexpected exception: '.$e->getMessage());
        }

    }

    /**
     * Test append.
     */
    public function testAppend()
    {
        $ruleContext = new RuleContext();
        $ruleContext->append(new RuleContext(array('foo' => 'bar')));
        $this->assertEquals(array('foo' => 'bar'), $ruleContext->getElements());
    }
    
}
