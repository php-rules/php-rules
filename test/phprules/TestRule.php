<?php

use phprules\Operator;
use phprules\Rule;
use phprules\RuleContext;
use phprules\RuleSet;

class TestRule extends UnitTestCase {

	public function testRuleName()
	{
		$rule = new Rule('testRuleName');
		$expected = 'testRuleName';
		$this->assertEqual($expected, $rule->getName());
	}

	// Test Integer Variable Evaluations
	public function testVariableIntegerEvaluation(){
		// Create the rule
		$rule = new Rule('eligibleForGroupDiscount');

		// Declare the minimun number of people required for discount
		$rule->addVariable('minNumPeople', 6);

		// Declare a "placeholder" variable for the actual number of people
		$rule->addVariable('actualNumPeople', 0);

		// Compare the two, i.e.,
		// minNumPeople >= actualNumPeople
		//$rule->addOperator('GREATERTHANOREQUALTO');
		$rule->addOperator(Operator::GREATER_THAN_OR_EQUAL_TO);

		// Create a RuleContext, i.e., a "Fact"
		$ruleContext = new RuleContext('eligibleForGroupDiscountFact');

		// Declare the minimun number of people required for discount
		$ruleContext->addVariable('minNumPeople', 6);

		// How many people are there?
		$ruleContext->addVariable('actualNumPeople', 5);

		// Evaluate
		$result = $rule->evaluate($ruleContext);

		$this->assertFalse($result->value);
	}

	// Test Float Variable Evaluations
	public function testVariableFloatEvaluation()
	{
		// Create the rule
		$rule = new Rule('eligibleForGroupDiscount');

		// Declare the minimun number of people required for discount
		$rule->addVariable('actTotal', 0.0);

		// Declare a "placeholder" variable for the actual number of people
		$rule->addVariable('minTotal', 0.0);

		// Compare the two, i.e.,
		// actTotal >= minTotal
		//$rule->addOperator('GREATERTHANOREQUALTO');
		$rule->addOperator(Operator::GREATER_THAN_OR_EQUAL_TO);

		// Create a RuleContext, i.e., a "Fact"
		$ruleContext = new RuleContext('eligibleForGroupDiscountFact');

		// How many people are there?
		$ruleContext->addVariable('actTotal', 102.83);

		// Declare the minimun number of people required for discount
		$ruleContext->addVariable('minTotal', 100.01);

		// Evaluate
		$result = $rule->evaluate($ruleContext);

		$this->assertFalse($result->value);
	}

	// Test Propositions Evaluations
	public function testPropositions()
	{
		// Create the rule
		$rule = new Rule('eligibleForGroupDiscount');

		// Declare the minimun number of people required for discount
		$rule->addVariable('actTotal', 0.0);

		// Declare a "placeholder" variable for the actual number of people
		$rule->addVariable('minTotal', 0.0);

		// Compare the two, i.e.,
		// actTotal >= minTotal
		//$rule->addOperator('GREATERTHANOREQUALTO');
		$rule->addOperator(Operator::GREATER_THAN_OR_EQUAL_TO);

		// Create a RuleContext, i.e., a "Fact"
		$ruleContext = new RuleContext('eligibleForGroupDiscountFact');

		// How many people are there?
		$ruleContext->addVariable('actTotal', 102.83);

		// Declare the minimun number of people required for discount
		$ruleContext->addVariable('minTotal', 100.01);

		// Evaluate
		$result = $rule->evaluate($ruleContext);

		$this->assertFalse($result->value);
	}

  public function testIn() {
      // rule with default null brand
      $brandRule = new Rule( 'brandRule' );
      $brandRule->addVariable( 'brandList', array() );
      $brandRule->addVariable( 'brand', null );
      $brandRule->addOperator( "IN" );

      // there might be other rules, etc to add...
      $finalRuleSet = new RuleSet ( 'finalRuleSet' );
      $finalRuleSet->addRule( $brandRule );


      // the actual values...
      $brandRuleSetContext = new RuleContext( 'brandRuleSetContex' );
      $brandRuleSetContext->addVariable( 'brand', 'yoo' );
      $brandRuleSetContext->addVariable( 'brandList', array('yoo', 'foo', 'bar') );

      // collect all variables
      $finalRuleSetContext = new RuleContext( 'finalRuleSetContex' );
      $finalRuleSetContext->append( $brandRuleSetContext );


      $proposition = $finalRuleSet->evaluate( $finalRuleSetContext );
      $this->assertTrue($proposition->value);
  }

	// Test complex rule evaluation
	public function test_Rule_IsEligibleForUpgrade_PassengerIsEconomy_TRUE()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$p = $rule->evaluate($ruleContext);

		$this->assertTrue($p->value);
	}

	public function test_Rule_IsEligibleForUpgrade_PassengerIsEconomy_FALSE()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$ruleContext->elements['passengerIsEconomy']->value = FALSE;
		$p = $rule->evaluate($ruleContext);

		$this->assertFalse($p->value);
	}

	public function test_Rule_IsEligibleForUpgrade_PassengerIsGoldCardHolder_FALSE()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$ruleContext->elements['passengerIsGoldCardHolder']->value = FALSE;
		$p = $rule->evaluate($ruleContext);

		$this->assertTrue($p->value);
	}

	public function test_Rule_IsEligibleForUpgrade_passengerIsSilverCardHolder_FALSE()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$ruleContext->elements['passengerIsSilverCardHolder']->value = FALSE;
		$p = $rule->evaluate($ruleContext);

		$this->assertTrue($p->value);
	}

	public function test_Rule_IsEligibleForUpgrade_passengerIsNotCardHolder()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$ruleContext->elements['passengerIsGoldCardHolder']->value = FALSE;
		$ruleContext->elements['passengerIsSilverCardHolder']->value = FALSE;
		$p = $rule->evaluate($ruleContext);

		$this->assertFalse($p->value);
	}

	public function test_Rule_IsEligibleForUpgrade_passengerCarryOnBaggageTooHeavy()
	{
		$rule = $this->getRuleIsEligibleForUpgrade();
		$ruleContext = $this->getRuleContextIsEligibleForUpgrade();
		$ruleContext->elements['passengerCarryOnBaggageWeight']->value = 15.1;
		$p = $rule->evaluate($ruleContext);

		$this->assertFalse($p->value);
	}

	private function getRuleIsEligibleForUpgrade()
	{
		$rule = new Rule('IsEligibleForUpgrade');
		$rule->addProposition('passengerIsEconomy', TRUE);
		$rule->addProposition('passengerIsGoldCardHolder', TRUE);
		$rule->addProposition('passengerIsSilverCardHolder', TRUE);
		$rule->addOperator(Operator::LOGICAL_OR);
		$rule->addOperator(Operator::LOGICAL_AND);
		$rule->addVariable('passengerCarryOnBaggageAllowance', 15.0);
		$rule->addVariable('passengerCarryOnBaggageWeight', 10.0);
		$rule->addOperator(Operator::LESS_THAN_OR_EQUAL_TO);
		$rule->addOperator(Operator::LOGICAL_AND);
		return $rule;
	}

	private function getRuleContextIsEligibleForUpgrade()
	{
		$ruleContext = new RuleContext('IsEligibleForUpgrade');
		$ruleContext->addProposition('passengerIsEconomy', TRUE);
		$ruleContext->addProposition('passengerIsGoldCardHolder', TRUE);
		$ruleContext->addProposition('passengerIsSilverCardHolder', TRUE);
		$ruleContext->addVariable('passengerCarryOnBaggageAllowance', 15.0);
		$ruleContext->addVariable('passengerCarryOnBaggageWeight', 10.0);
		return $ruleContext;
	}

}
