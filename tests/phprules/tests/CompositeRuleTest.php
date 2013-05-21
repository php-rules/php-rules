<?php
namespace phprules\tests;

use phprules\operators\Comparison;
use phprules\operators\Logical;
use phprules\Rule;
use phprules\RuleContext;
use phprules\CompositeRule;

class CompositeRuleTest extends \PHPUnit_Framework_TestCase
{
    public function testAnd()
    {
        // rule A
        $ruleA = new Rule('ruleA');
        $ruleA->addVariable('varA1');
        $ruleA->addVariable('varA2');
        $ruleA->addOperator(Comparison::EQUAL_TO);

        // rule B
        $ruleB = new Rule('ruleB');
        $ruleB->addVariable('varB1');
        $ruleB->addVariable('varB2');
        $ruleB->addOperator(Comparison::EQUAL_TO);
        $ruleB->addOperator(Logical::LOGICAL_NOT);

        // put together
        $compositeRule = new CompositeRule('compositeRule', Logical::LOGICAL_AND);
        $compositeRule->addRule($ruleA);
        $compositeRule->addRule($ruleB);

        // the context
        $ruleContext = new RuleContext();
        $ruleContext->addElement('varA1', 'yoo');
        $ruleContext->addElement('varA2', 'yoo');
        $ruleContext->addElement('varB1', 'foo');
        $ruleContext->addElement('varB2', 'bar');

        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);
        $this->assertEquals('( ( varA1 == varA2 ) AND ( NOT ( varB1 == varB2 ) ) )', $result->getName());

        // make A fail
        $ruleContext->addElement('varA2', 'xxx');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);

        // make B fail
        $ruleContext->addElement('varA2', 'yoo');
        $ruleContext->addElement('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);

        // make A and B fail
        $ruleContext->addElement('varA2', 'xxx');
        $ruleContext->addElement('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);
        $this->assertEquals('( ( varA1 == varA2 ) AND ( NOT ( varB1 == varB2 ) ) )', $result->getName());
    }

    public function testOr()
    {
        // rule A
        $ruleA = new Rule('ruleA');
        $ruleA->addVariable('varA1');
        $ruleA->addVariable('varA2');
        $ruleA->addOperator(Comparison::EQUAL_TO);

        // rule B
        $ruleB = new Rule('ruleB');
        $ruleB->addVariable('varB1');
        $ruleB->addVariable('varB2');
        $ruleB->addOperator(Comparison::EQUAL_TO);
        $ruleB->addOperator(Logical::LOGICAL_NOT);

        // put together
        $compositeRule = new CompositeRule('compositeRule', Logical::LOGICAL_OR);
        $compositeRule->addRule($ruleA);
        $compositeRule->addRule($ruleB);

        // the context
        $ruleContext = new RuleContext(array(
            'varA1' => 'yoo',
            'varA2' => 'yoo',
            'varB1' => 'foo',
            'varB2' => 'bar',
        ));

        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);
        $this->assertEquals('( ( varA1 == varA2 ) OR ( NOT ( varB1 == varB2 ) ) )', $result->getName());

        // make A fail
        $ruleContext->addElement('varA2', 'xxx');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make B fail
        $ruleContext->addElement('varA2', 'yoo');
        $ruleContext->addElement('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make A and B fail
        $ruleContext->addElement('varA2', 'xxx');
        $ruleContext->addElement('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);
    }
}
