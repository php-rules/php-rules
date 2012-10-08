<?php
namespace phprules\tests;

use phprules\Rule;
use phprules\RuleContext;
use phprules\Variable;
use phprules\operators\Comparison;
use phprules\operators\Logical;

class VariableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test toString.
     */
    public function testToString()
    {
        $this->assertEquals('[phprules\Variable name=maxNumPeople, value=10]', new Variable('maxNumPeople', 10));
    }

    /**
     * Test not equalTo.
     */
    public function testNotEqualTo()
    {
        $rule = new Rule('test');
        $rule->addVariable('actNumPeople');
        $rule->addVariable('otherNumPeople');
        $rule->addOperator(Comparison::EQUAL_TO);
        $rule->addOperator(Logical::LOGICAL_NOT);
        $p = $rule->evaluate(new RuleContext(array(
            'actNumPeople' => 10,
            'otherNumPeople' => 10,
        )));
        $this->assertFalse($p->getValue());
    }

}
