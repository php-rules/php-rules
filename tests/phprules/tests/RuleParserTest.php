<?php
namespace phprules\tests;

use phprules\Proposition;
use phprules\Rule;
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
     * Test simple rule.
     */
    public function testSimple()
    {
        $ruleString = "( ( C == 'foo' ) AND ( ( B == 'bar' ) OR A ) )";
        $ruleLoader = new RuleLoader();
        $ruleContext = $ruleLoader->loadRuleContext(new MemorySource(array(
            'C EQUALS foo',
            'B EQUALS bar',
            'A IS true',
        )));
        var_dump($ruleContext);

        $ruleParser = new RuleParser();
        $rule = $ruleParser->parse($ruleString);
        $this->assertNotNull($rule);
        $this->assertTrue($rule instanceof Rule);

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertNotNull($p->getName());
        $this->assertTrue($p->value);
        $this->assertEquals($ruleString, $p->getName());
    }

}
