<?php
namespace phprules\tests;

use phprules\Proposition;
use phprules\Rule;
use phprules\RuleContext;
use phprules\RuleLoader;
use phprules\RuleParser;
use phprules\source\MemorySource;

class RuleParserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * Test token.
     */
    public function testToken()
    {
        $ruleParser = new RuleParser();
    }

    /**
     * Test parseToken().
     */
    public function testParseToken()
    {
        $ruleStrings = array(
            "( ( C == 'foo' ) AND ( ( B == 'bar' ) OR A ) )",
            "((C=='foo')AND((B=='bar')OR A))",
            "((C       =='foo')             AND((B=='bar')OR A))        ",
        );
        $expected = array('(', '(', 'C', '==', "'foo'", ')', 'AND', '(', '(', 'B', '==', "'bar'", ')', 'OR', 'A', ')', ')');

        $ruleParser = new RuleParser();
        foreach ($ruleStrings as $ruleString) {
            $token = $ruleParser->parseToken($ruleString);
            $this->assertEquals($expected, $token);
        }
    }
    
    /**
     * Test parseRule().
     */
    public function XtestParseRule()
    {
        $ruleString = "( ( A AND B ) OR C )";

        $ruleParser = new RuleParser();
        $rule = $ruleParser->parseRule($ruleString);
        $this->assertNotNull($rule);
        $this->assertTrue($rule instanceof Rule);
        
        $ruleContext = new RuleContext(array('A' => true, 'B' => true, 'C' => false));
        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertNotNull($p->getName());
        $this->assertTrue($p->value);
        $this->assertEquals($ruleString, $p->getName());
    }

}
