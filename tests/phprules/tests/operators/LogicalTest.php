<?php
namespace phprules\tests\operators;

use phprules\Variable;
use phprules\Proposition;
use phprules\operators\Logical;

class LogicalTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Get all stack combinations.
     */
    protected function getStacks() {
        return array(
            array(
                new Proposition('first', true), 
                new Proposition('second', false),
            ),
            array(
                new Proposition('first', false), 
                new Proposition('second', true),
            ),
            array(
                new Proposition('first', true), 
                new Proposition('second', true),
            ),
            array(
                new Proposition('first', false), 
                new Proposition('second', false),
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
            $pstack = Logical::perform($operator, $stack);
            $this->assertNotNull($pstack);
            $this->assertEquals(1, count($pstack));
            $proposition = $pstack[0];
            $this->assertEquals($expected, $proposition->getValue());
            $this->assertEquals($expectedName, $proposition->getName());
        }
    }
    
    /**
     * Test OR
     */
    public function testOr()
    {
        $expectedResults = array(true, true, true, false);
        $expectedName = "( first OR second )";
        $this->doTest(Logical::LOGICAL_OR, $expectedResults, $expectedName);
    }

    /**
     * Test AND
     */
    public function testAnd()
    {
        $expectedResults = array(false, false, true, false);
        $expectedName = "( first AND second )";
        $this->doTest(Logical::LOGICAL_AND, $expectedResults, $expectedName);
    }
    
    /**
     * Test XOR
     */
    public function testXor()
    {
        $expectedResults = array(true, true, false, false);
        $expectedName = "( first XOR second )";
        $this->doTest(Logical::LOGICAL_XOR, $expectedResults, $expectedName);
    }
    
    /**
     * Test NOT
     */
    public function testNot()
    {
        $expectedResults = array(false, true, false, true);
        $expectedName = "( NOT first )";
        $this->doTest(Logical::LOGICAL_NOT, $expectedResults, $expectedName, true);
    }
    
}
