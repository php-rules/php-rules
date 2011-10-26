<?php

use phprules\Proposition;

class TestProposition extends UnitTestCase {

	public function setUp()
	{
    parent::setUp();
		$this->p  = new Proposition('goldCardHolder', TRUE);
		$this->p1 = new Proposition('silverCardHolder', TRUE);
		$this->p2 = new Proposition('isInFirstClass', FALSE);
  }

	public function testPropositionName()
	{
		$this->assertEqual($this->p->getName(), 'goldCardHolder');
	}

	public function testPropositionValue()
	{
		$this->assertTrue($this->p->value);
	}

	public function testPropositionToString()
	{
		$msg = '[Proposition statement=goldCardHolder, value=true]';
		$this->assertEqual($this->p, $msg);
	}

	public function testPropositionLogicalAndTrue()
	{
		$p = $this->p->logicalAnd($this->p1);
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalAndFalse()
	{
		$p = $this->p->logicalAnd($this->p2);
		$this->assertFalse($p->value);
	}

	public function testPropositionLogicalOr_1_True_1_True()
	{
		$p = $this->p->logicalOr($this->p2);
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalOr_1_True_1_False()
	{
		$p = $this->p2->logicalOr($this->p);
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalOr_1_False_1_False()
	{
		$p = $this->p2->logicalOr($this->p2);
		$this->assertFalse($p->value);
	}

	public function testPropositionLogicalNot_True()
	{
		$p = $this->p2->logicalNot();
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalNot_False()
	{
		$p = $this->p->logicalNot();
		$this->assertFalse($p->value);
	}

	public function testPropositionLogicalXor_True_True_FALSE()
	{
		$p = $this->p->logicalXor($this->p1);
		$this->assertFalse($p->value);
	}

	public function testPropositionLogicalXor_False_True_TRUE()
	{
		$p = $this->p2->logicalXor($this->p1);
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalXor_True_False_TRUE()
	{
		$p = $this->p1->logicalXor($this->p2);
		$this->assertTrue($p->value);
	}

	public function testPropositionLogicalXor_False_False_FALSE()
	{
		$p = $this->p->logicalXor($this->p1);
		$this->assertFalse($p->value);
	}
}
