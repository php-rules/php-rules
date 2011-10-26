<?php

use phprules\RuleLoader;
use phprules\strategy\FileLoaderStrategy;

define ('FCPATH', dirname(__FILE__).'/');
class TestFileLoaderStrategy extends UnitTestCase {

	public function setUp()
	{
    parent::setUp();
		$this->loader = new RuleLoader();
		$this->strategy = new FileLoaderStrategy();
		$this->dataFolderPath = FCPATH.'data/';
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
		$rule = $this->loader->loadRule($this->dataFolderPath . 'SuitableForUpgrade.rul');

		$this->assertNotNull($rule);
		$this->assertNotNull($rule->elements);
		$this->assertEqual(9, count($rule->elements));
	}

	public function test_RuleLoader_loadRuleContext()
	{
		$this->loader->setStrategy($this->strategy);
		$ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . 'SuitableForUpgrade.con',4);

		$this->assertNotNull($ruleContext);
		$this->assertNotNull($ruleContext->elements);
		$this->assertEqual(5, count($ruleContext->elements));
	}

	public function test_RuleLoader_evaluate()
	{
		$this->loader->setStrategy($this->strategy);
		$rule = $this->loader->loadRule($this->dataFolderPath . 'SuitableForUpgrade.rul');
		$ruleContext = $this->loader->loadRuleContext($this->dataFolderPath . 'SuitableForUpgrade.con',4);
		$p = $rule->evaluate($ruleContext);

		$this->assertNotNull($p);
		$this->assertEqual('Proposition', $p->getType());
		$this->assertTrue($p->value);
		$this->assertNotNull($p->getName());
	}
}
