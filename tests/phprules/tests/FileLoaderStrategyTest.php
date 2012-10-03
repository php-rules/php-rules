<?php
namespace phprules\tests;

use phprules\Proposition;
use phprules\RuleLoader;
use phprules\strategy\FileLoaderStrategy;

class FileLoaderStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->loader = new RuleLoader();
        $this->strategy = new FileLoaderStrategy();
        $this->dataFolderPath = __DIR__.'/data';
    }

    public function testConstructors()
    {
        $this->assertNotNull($this->loader);
        $this->assertNotNull($this->strategy);
    }

    public function testSetStrategy()
    {
        $this->loader->setStrategy($this->strategy);
    }

    public function testLoadRule()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/sfu/rule.txt');

        $this->assertNotNull($rule);
        $this->assertNotNull($rule->getElements());
        $this->assertEquals(9, count($rule->getElements()));
    }

    public function testLoadRuleContext()
    {
        $this->loader->setStrategy($this->strategy);
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/sfu/context-true.txt');

        $this->assertNotNull($ruleContext);
        $this->assertNotNull($ruleContext->elements);
        $this->assertEquals(5, count($ruleContext->elements));
    }

    public function testEvaluateSfu()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/sfu/rule.txt');
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/sfu/context-true.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertTrue($p->value);
        $this->assertNotNull($p->getName());

        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/sfu/context-false.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertFalse($p->value);
        $this->assertNotNull($p->getName());
    }

    public function testEvaluateSfuSimplified()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/sfu-simplified/rule.txt');
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/sfu-simplified/context-true.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertTrue($p->value);
        $this->assertNotNull($p->getName());

        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/sfu-simplified/context-false.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertFalse($p->value);
        $this->assertNotNull($p->getName());
    }

    public function testNested()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/nested/rule.txt');
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/nested/context-true.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertTrue($p->value);

        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/nested/context-false.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertFalse($p->value);
    }

    public function testSimplified()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/simplified/rule.txt');
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/simplified/context-true.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertTrue($p->value);

        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/simplified/context-false.txt');

        $p = $rule->evaluate($ruleContext);
        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertFalse($p->value);
    }

}
