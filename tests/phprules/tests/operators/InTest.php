<?php
namespace phprules\tests\operators;

use phprules\Variable;
use phprules\operators\In;

class InTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test IN positive
     */
    public function testInPositive()
    {
        $stack = array(
            new Variable('first', 'foo'), 
            new Variable(null, array('foo', 'bar')), 
        );
        
        // positive
        $pstack = In::perform(In::IN, $stack);
        $this->assertNotNull($pstack);
        $this->assertEquals(1, count($pstack));
        $proposition = $pstack[0];
        $this->assertTrue($proposition->getValue());
        $this->assertEquals("( first IN ( foo,bar ) )", $proposition->getName());
    }   
    
    /**
     * Test IN negative
     */
    public function testInNegative()
    {
        $stack = array(
            new Variable('first', 'abc'), 
            new Variable('arr', array('foo', 'bar')), 
        );
    
        $pstack = In::perform(In::IN, $stack);
        $this->assertNotNull($pstack);
        $this->assertEquals(1, count($pstack));
        $proposition = $pstack[0];
        $this->assertFalse($proposition->getValue());
        $this->assertEquals("( first IN ( foo,bar ) )", $proposition->getName());
    }

    /**
     * Test IN string
     */
    public function testInString()
    {
        $stack = array(
            new Variable('first', 'foo'), 
            new Variable('arr', 'foo,bar,deng'),
        );
    
        $pstack = In::perform(In::IN, $stack);
        $this->assertNotNull($pstack);
        $this->assertEquals(1, count($pstack));
        $proposition = $pstack[0];
        $this->assertTrue($proposition->getValue());
        $this->assertEquals("( first IN ( foo,bar,deng ) )", $proposition->getName());
    }

}

