<?php
namespace phprules\tests;

use phprules\DateVariable;
use phprules\Rule;
use phprules\RuleContext;
use phprules\operators\Comparison;
use phprules\operators\Logical;

class DateVariableTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->date = new DateVariable('dateNow', 'now');
    }

    /**
     * Test equalTo.
     */
    public function testEqualTo()
    {
        $now = new DateVariable('dateNow', 'now');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($now);
        $rule->addOperator(Comparison::EQUAL_TO);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

    /**
     * Test not equalTo.
     */
    public function testNotEqualTo()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($then);
        $rule->addOperator(Comparison::EQUAL_TO);
        $rule->addOperator(Logical::LOGICAL_NOT);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);

        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($lastWednesday);
        $rule->addOperator(Comparison::EQUAL_TO);
        $rule->addOperator(Logical::LOGICAL_NOT);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

    /**
     * Test lessThan.
     */
    public function testLessThan()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $rule = new Rule('test');
        $rule->addVariable($then);
        $rule->addVariable($this->date);
        $rule->addOperator(Comparison::LESS_THAN);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

    /**
     * Test lessThanOrEqualTo.
     */
    public function testLessThanOrEqualTo()
    {
        $now = new DateVariable('dateNow', 'now');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($now);
        $rule->addOperator(Comparison::LESS_THAN_OR_EQUAL_TO);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

    /**
     * Test greaterThan.
     */
    public function testGreaterThan()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($then);
        $rule->addOperator(Comparison::GREATER_THAN);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

    /**
     * Test greaterThanOrEqualTo.
     */
    public function testGreaterThanOrEqualTo()
    {
        $now = new DateVariable('dateNow', 'now');
        $rule = new Rule('test');
        $rule->addVariable($this->date);
        $rule->addVariable($now);
        $rule->addOperator(Comparison::GREATER_THAN_OR_EQUAL_TO);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->value);
    }

}
