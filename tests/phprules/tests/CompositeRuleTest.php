<?php
namespace phprules\tests;

use phprules\Operator;
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
        $ruleA->addOperator(Operator::EQUAL_TO);

        // rule B
        $ruleB = new Rule('ruleB');
        $ruleB->addVariable('varB1');
        $ruleB->addVariable('varB2');
        $ruleB->addOperator(Operator::NOT_EQUAL_TO);

        // put together
        $compositeRule = new CompositeRule('compositeRule', CompositeRule::EVALUATE_AND);
        $compositeRule->addRule($ruleA);
        $compositeRule->addRule($ruleB);

        // the context
        $ruleContext = new RuleContext('ruleContext');
        $ruleContext->addVariable('varA1', 'yoo');
        $ruleContext->addVariable('varA2', 'yoo');
        $ruleContext->addVariable('varB1', 'foo');
        $ruleContext->addVariable('varB2', 'bar');

        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make A fail
        $ruleContext->addVariable('varA2', 'xxx');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);

        // make B fail
        $ruleContext->addVariable('varA2', 'yoo');
        $ruleContext->addVariable('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);

        // make A and B fail
        $ruleContext->addVariable('varA2', 'xxx');
        $ruleContext->addVariable('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);
    }

    public function testOr()
    {
        // rule A
        $ruleA = new Rule('ruleA');
        $ruleA->addVariable('varA1');
        $ruleA->addVariable('varA2');
        $ruleA->addOperator(Operator::EQUAL_TO);

        // rule B
        $ruleB = new Rule('ruleB');
        $ruleB->addVariable('varB1');
        $ruleB->addVariable('varB2');
        $ruleB->addOperator(Operator::NOT_EQUAL_TO);

        // put together
        $compositeRule = new CompositeRule('compositeRule', CompositeRule::EVALUATE_OR);
        $compositeRule->addRule($ruleA);
        $compositeRule->addRule($ruleB);

        // the context
        $ruleContext = new RuleContext('ruleContext');
        $ruleContext->addVariable('varA1', 'yoo');
        $ruleContext->addVariable('varA2', 'yoo');
        $ruleContext->addVariable('varB1', 'foo');
        $ruleContext->addVariable('varB2', 'bar');

        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make A fail
        $ruleContext->addVariable('varA2', 'xxx');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make B fail
        $ruleContext->addVariable('varA2', 'yoo');
        $ruleContext->addVariable('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertTrue($result->value);

        // make A and B fail
        $ruleContext->addVariable('varA2', 'xxx');
        $ruleContext->addVariable('varB2', 'foo');
        $result = $compositeRule->evaluate($ruleContext);
        $this->assertFalse($result->value);
    }
}
