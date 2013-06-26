<?php
namespace phprules\tests;

use phprules\Proposition;
use phprules\RuleContext;
use phprules\RuleLoader;
use phprules\source\MemorySource;

class RuleSyntaxTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test a rule.
     */
    protected function doTestRule($rule, $positive, $negative, $expectedName = null)
    {
        $loader = new RuleLoader();
        $rule = $loader->loadRule(new MemorySource($rule));
        $p = $rule->evaluate($loader->loadRuleContext(new MemorySource($positive)));
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertNotNull($p->getName());
        $this->assertTrue($p->value);
        if ($expectedName) {
            $this->assertEquals($expectedName, $p->getName());
        }

        $p = $rule->evaluate($loader->loadRuleContext(new MemorySource($negative)));
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertNotNull($p->getName());
        $this->assertFalse($p->value);
    }

    public function testEvaluateSfu()
    {
        $rule = "
passengerIsEconomy IS true
passengerIsGoldCardHolder IS true
passengerIsSilverCardHolder IS true
OR
AND
passengerCarryOnBaggageWeight EQUALS y
passengerCarryOnBaggageAllowance EQUALS x
LESSTHANOREQUALTO
AND
";
        $positive ="
passengerIsEconomy IS true      
passengerIsGoldCardHolder IS true
passengerIsSilverCardHolder IS false    
passengerCarryOnBaggageWeight EQUALS 10.0
passengerCarryOnBaggageAllowance EQUALS 15.0
";
        $negative ="
passengerIsEconomy IS true      
passengerIsGoldCardHolder IS false
passengerIsSilverCardHolder IS false    
passengerCarryOnBaggageWeight EQUALS 10.0
passengerCarryOnBaggageAllowance EQUALS 15.0
";
        $expectedName = "( ( passengerIsEconomy AND ( passengerIsGoldCardHolder OR passengerIsSilverCardHolder ) ) AND ( passengerCarryOnBaggageWeight <= passengerCarryOnBaggageAllowance ) )";
        
        $this->doTestRule($rule, $positive, $negative, $expectedName);
    }

    public function testEvaluateSfuSimplified()
    {
        $rule = "
# Rule establishing when an airline passenger may be
# allowed to upgrade his/her seat.

BOOL passengerIsEconomy
BOOL passengerIsGoldCardHolder
BOOL passengerIsSilverCardHolder
OR
AND
VAR passengerCarryOnBaggageWeight
VAR passengerCarryOnBaggageAllowance
LESSTHANOREQUALTO
AND
";
        $positive ="
passengerIsEconomy IS true      
passengerIsGoldCardHolder IS true
passengerIsSilverCardHolder IS false    
passengerCarryOnBaggageWeight EQUALS 10.0
passengerCarryOnBaggageAllowance EQUALS 15.0        
";
        $negative ="
passengerIsEconomy IS false      
passengerIsGoldCardHolder IS true
passengerIsSilverCardHolder IS false    
passengerCarryOnBaggageWeight EQUALS 10.0
passengerCarryOnBaggageAllowance EQUALS 15.0        
";
        $expectedName = "( ( passengerIsEconomy AND ( passengerIsGoldCardHolder OR passengerIsSilverCardHolder ) ) AND ( passengerCarryOnBaggageWeight <= passengerCarryOnBaggageAllowance ) )";
        
        $this->doTestRule($rule, $positive, $negative, $expectedName);
    }

    public function testNested()
    {
        $rule = "
# nested conditions
# ( C OR ( B AND A ) )

A IS true
B IS true
AND
C IS false
OR
";
        $positive ="
A IS true
B IS false
C IS true
";
        $negative ="
A IS false
B IS true
C IS false
";
        $expectedName = "( ( A AND B ) OR C )";
        
        $this->doTestRule($rule, $positive, $negative, $expectedName);
    }

    public function testSimplified()
    {
        $rule = "
BOOL A
VAR B
CONST bar
EQUALTO
OR
VAR C
CONST foo
EQUALTO
AND
";
        $positive ="
A IS false
B EQUALS bar
C EQUALS foo
";
        $negative ="
A IS false
B EQUALS xxx
C EQUALS foo
";
        $expectedName = "( ( A OR ( B == 'bar' ) ) AND ( C == 'foo' ) )";

        $this->doTestRule($rule, $positive, $negative, $expectedName);
    }

    public function testSimplifiedReverse()
    {
        $rule = "
BOOL A
CONST bar
VAR B
EQUALTO
OR
CONST foo
VAR C
EQUALTO
AND
";
        $positive ="
A IS false
B EQUALS bar
C EQUALS foo
";
        $negative ="
A IS false
B EQUALS xxx
C EQUALS foo
";
        $expectedName = "( ( A OR ( 'bar' == B ) ) AND ( 'foo' == C ) )";

        $this->doTestRule($rule, $positive, $negative, $expectedName);
    }

    public function testCompare()
    {
        $rule = "
VAR actualNumPeople
VAR minNumPeople
GREATERTHANOREQUALTO
BOOL freeForAll
OR
";
        $positive ="
minNumPeople EQUALS 4
actualNumPeople EQUALS 7
freeForAll IS false
";
        $negative ="
minNumPeople EQUALS 4
actualNumPeople EQUALS 3
freeForAll IS false
";
        $expectedName = "( ( actualNumPeople >= minNumPeople ) OR freeForAll )";

        $this->doTestRule($rule, $positive, $negative);
    }

    public function testVarList()
    {
        $rule = "
VAR colour
VAR colourList
IN
";
        $positive ="
colour EQUALS blue
colourList EQUALS blue,green,red,yellow
";
        $negative ="
colour EQUALS pink
colourList EQUALS blue,green,red,yellow
";
        $expectedName = "( colour IN colourList )";

        $this->doTestRule($rule, $positive, $negative);
    }

    public function testConstList()
    {
        $rule = "
VAR colour
CONST blue,green,red,yellow
IN
";
        $positive ="
colour EQUALS blue
";
        $negative ="
colour EQUALS pink
";
        $expectedName = "( colour IN 'blue,green,red,yellow' )";

        $this->doTestRule($rule, $positive, $negative);
    }

}
