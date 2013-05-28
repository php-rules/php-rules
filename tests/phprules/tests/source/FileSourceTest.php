<?php
namespace phprules\tests\source;

use phprules\RuleLoader;
use phprules\source\FileSource;

class FileSourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $this->loader = new RuleLoader();
        $this->dataFolderPath = __DIR__.'/data';
    }

    /**
     * Test load rule
     */
    public function testLoadRule()
    {
        $rule = $this->loader->loadRule(new FileSource($this->dataFolderPath.'/rule1.txt'));
        $this->assertNotNull($rule);
        $this->assertNotNull($rule->getElements());
        $this->assertEquals(9, count($rule->getElements()));

        $rule = $this->loader->loadRule(new FileSource($this->dataFolderPath.'/rule2.txt'));
        $this->assertNotNull($rule);
        $this->assertNotNull($rule->getElements());
        $this->assertEquals(9, count($rule->getElements()));
    }

    /**
     * Test load context
     */
    public function testLoadRuleContext()
    {
        $ruleContext = $this->loader->loadRuleContext(new FileSource($this->dataFolderPath.'/context1.txt'));
        $this->assertNotNull($ruleContext);
        $this->assertNotNull($ruleContext->getElements());
        $this->assertEquals(5, count($ruleContext->getElements()));


        $ruleContext = $this->loader->loadRuleContext(new FileSource($this->dataFolderPath.'/context2.txt'));
        $this->assertNotNull($ruleContext);
        $this->assertNotNull($ruleContext->getElements());
        $this->assertEquals(3, count($ruleContext->getElements()));
    }

}
