<?php
namespace phprules\tests;

use phprules\Proposition;
use phprules\Rule;
use phprules\RuleContext;
use phprules\operators\Logical;

class PropositionTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->pg = new Proposition('goldCardHolder', true);
        $this->ps = new Proposition('silverCardHolder', true);
        $this->pff = new Proposition('isInFirstClass', false);
    }

    public function testPropositionName()
    {
        $this->assertEquals($this->pg->getName(), 'goldCardHolder');
    }

    public function testPropositionValue()
    {
        $this->assertTrue($this->pg->getValue());
    }

    public function testPropositionToString()
    {
        $this->assertEquals('[phprules\Proposition statement=goldCardHolder, value=true]', $this->pg);
    }

    public function testAndTrue()
    {
        $rule = new Rule('test');
        $rule->addProposition($this->pg);
        $rule->addProposition($this->ps);
        $rule->addOperator(Logical::LOGICAL_AND);
        $p = $rule->evaluate(new RuleContext());
        $this->assertTrue($p->getValue());
    }

}
