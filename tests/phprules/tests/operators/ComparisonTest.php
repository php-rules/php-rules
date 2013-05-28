<?php
namespace phprules\tests\operators;

use phprules\Variable;
use phprules\operators\Comparison;

class ComparisonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Get some stacks.
     */
    protected function getStacks() {
        return array(
            array(
                new Variable('first', 1), 
                new Variable('second', 2),
            ),
            array(
                new Variable('first', 2), 
                new Variable('second', 1),
            ),
            array(
                new Variable('first', 3), 
                new Variable('second', 3),
            ),
        );
    }

    /**
     * Do some testing.
     */
    protected function doTest($operator, $expectedResults, $expectedName, $popStack  = false)
    {
        foreach ($this->getStacks() as $ii => $stack) {
            if ($popStack) {
                array_pop($stack);
            }
            $expected = $expectedResults[$ii];
            $pstack = Comparison::perform($operator, $stack);
            $this->assertNotNull($pstack);
            $this->assertEquals(1, count($pstack));
            $proposition = $pstack[0];
            $this->assertEquals($expected, $proposition->getValue());
            $this->assertEquals($expectedName, $proposition->getName());
        }
    }
    
    /**
     * Test equal to
     */
    public function testEqualTo()
    {
        $expectedResults = array(false, false, true);
        $expectedName = "( first == second )";
        $this->doTest(Comparison::EQUAL_TO, $expectedResults, $expectedName);
    }

    /**
     * Test less than
     */
    public function testLessThan()
    {
        $expectedResults = array(true, false, false);
        $expectedName = "( first < second )";
        $this->doTest(Comparison::LESS_THAN, $expectedResults, $expectedName);
    }

    /**
     * Test less than or equal to
     */
    public function testLessThanOrEqualTo()
    {
        $expectedResults = array(true, false, true);
        $expectedName = "( first <= second )";
        $this->doTest(Comparison::LESS_THAN_OR_EQUAL_TO, $expectedResults, $expectedName);
    }

    /**
     * Test greater than
     */
    public function testGreaterThan()
    {
        $expectedResults = array(false, true, false);
        $expectedName = "( first > second )";
        $this->doTest(Comparison::GREATER_THAN, $expectedResults, $expectedName);
    }

    /**
     * Test greater than or equal to
     */
    public function testGreaterThanOrEqualTo()
    {
        $expectedResults = array(false, true, true);
        $expectedName = "( first >= second )";
        $this->doTest(Comparison::GREATER_THAN_OR_EQUAL_TO, $expectedResults, $expectedName);
    }

}
