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

    public function test_RuleLoader_setStrategy()
    {
        $this->loader->setStrategy($this->strategy);
    }

    public function test_RuleLoader_loadRule()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/SuitableForUpgrade.rul');

        $this->assertNotNull($rule);
        $this->assertNotNull($rule->getElements());
        $this->assertEquals(9, count($rule->getElements()));
    }

    public function test_RuleLoader_loadRuleContext()
    {
        $this->loader->setStrategy($this->strategy);
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/SuitableForUpgrade.con', 4);

        $this->assertNotNull($ruleContext);
        $this->assertNotNull($ruleContext->elements);
        $this->assertEquals(5, count($ruleContext->elements));
    }

    public function test_RuleLoader_evaluate()
    {
        $this->loader->setStrategy($this->strategy);
        $rule = $this->loader->loadRule($this->dataFolderPath . '/SuitableForUpgrade.rul');
        $ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . '/SuitableForUpgrade.con',4);
        $p = $rule->evaluate($ruleContext);

        $this->assertNotNull($p);
        $this->assertTrue($p instanceof Proposition);
        $this->assertTrue($p->value);
        $this->assertNotNull($p->getName());
    }
}
