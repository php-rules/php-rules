<?php
namespace phprules\tests;

use phprules\operators\Comparison;
use phprules\operators\In;
use phprules\operators\Logical;
use phprules\Rule;
use phprules\RuleContext;
use phprules\CompositeRule;

class RuleTest extends \PHPUnit_Framework_TestCase
{

    public function testRuleName()
    {
        $rule = new Rule('testRuleName');
        $expected = 'testRuleName';
        $this->assertEquals($expected, $rule->getName());
    }

    // Test Integer Variable Evaluations
    public function testVariableIntegerEvaluation()
    {
        // Create the rule
        $rule = new Rule('eligibleForGroupDiscount');

        // Declare the minimun number of people required for discount
        $rule->addVariable('minNumPeople');

        // Declare a "placeholder" variable for the actual number of people
        $rule->addVariable('actualNumPeople');

        // Compare the two, i.e.,
        // minNumPeople >= actualNumPeople
        $rule->addOperator(Comparison::LESS_THAN_OR_EQUAL_TO);

        // Create a RuleContext, i.e., a "Fact"
        $ruleContext = new RuleContext();

        // Declare the minimun number of people required for discount
        $ruleContext->addElement('minNumPeople', 6);

        // How many people are there?
        $ruleContext->addElement('actualNumPeople', 4);

        // Evaluate
        $result = $rule->evaluate($ruleContext);

        $this->assertFalse($result->value);
        $this->assertEquals("( minNumPeople <= actualNumPeople )", $result->getName());
    }

    // Test Float Variable Evaluations
    public function testVariableFloatEvaluation()
    {
        // Create the rule
        $rule = new Rule('eligibleForGroupDiscount');

        // Declare the minimun number of people required for discount
        $rule->addVariable('actTotal');

        // Declare a "placeholder" variable for the actual number of people
        $rule->addVariable('minTotal');

        // Compare the two, i.e.,
        // actTotal >= minTotal
        $rule->addOperator(Comparison::GREATER_THAN_OR_EQUAL_TO);

        // Create a RuleContext, i.e., a "Fact"
        $ruleContext = new RuleContext();

        // How many people are there?
        $ruleContext->addElement('actTotal', 102.83);

        // Declare the minimun number of people required for discount
        $ruleContext->addElement('minTotal', 100.01);

        // Evaluate
        $result = $rule->evaluate($ruleContext);

        $this->assertTrue($result->value);
        $this->assertEquals("( actTotal >= minTotal )", $result->getName());
    }

    // Test Propositions Evaluations
    public function testPropositions()
    {
        // Create the rule
        $rule = new Rule('eligibleForGroupDiscount');

        // Declare the minimun number of people required for discount
        $rule->addVariable('actTotal');

        // Declare a "placeholder" variable for the actual number of people
        $rule->addVariable('minTotal');

        // Compare the two, i.e.,
        // actTotal >= minTotal
        $rule->addOperator(Comparison::GREATER_THAN_OR_EQUAL_TO);

        // Create a RuleContext, i.e., a "Fact"
        $ruleContext = new RuleContext();

        // How many people are there?
        $ruleContext->addElement('actTotal', 102.83);

        // Declare the minimun number of people required for discount
        $ruleContext->addElement('minTotal', 100.01);

        // Evaluate
        $result = $rule->evaluate($ruleContext);

        $this->assertTrue($result->value);
        $this->assertEquals("( actTotal >= minTotal )", $result->getName());
    }

  public function testIn()
  {
      // rule with default null brand
      $brandRule = new Rule('brandRule');
      $brandRule->addVariable('brand', null);
      $brandRule->addVariable('brandList', array());
      $brandRule->addOperator(In::IN);

      // there might be other rules, etc to add...
      $finalRuleSet = new CompositeRule ('finalRuleSet');
      $finalRuleSet->addRule($brandRule);

      // the actual values...
      $brandRuleSetContext = new RuleContext();
      $brandRuleSetContext->addElement('brand', 'yoo');
      $brandRuleSetContext->addElement('brandList', array('yoo', 'foo', 'bar'));

      // collect all variables
      $finalRuleSetContext = new RuleContext();
      $finalRuleSetContext->append($brandRuleSetContext);

      $proposition = $finalRuleSet->evaluate($finalRuleSetContext);
      $this->assertTrue($proposition->value);
  }

    // Test complex rule evaluation
    public function testIsEligibleForUpgradePassengerIsEconomyTrue()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $p = $rule->evaluate($ruleContext);

        $this->assertTrue($p->value);
    }

    public function testIsEligibleForUpgradePassengerIsEconomyFalse()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $ruleContext->addElement('passengerIsEconomy', false);
        $p = $rule->evaluate($ruleContext);

        $this->assertFalse($p->value);
    }

    public function testIsEligibleForUpgradePassengerIsGoldCardHolderFalse()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $ruleContext->addElement('passengerIsGoldCardHolder', false);
        $p = $rule->evaluate($ruleContext);

        $this->assertTrue($p->value);
    }

    public function testIsEligibleForUpgradepassengerIsSilverCardHolderFalse()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $ruleContext->addElement('passengerIsSilverCardHolder', false);
        $p = $rule->evaluate($ruleContext);

        $this->assertTrue($p->value);
    }

    public function testIsEligibleForUpgradePassengerIsNotCardHolder()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $ruleContext->addElement('passengerIsGoldCardHolder', false);
        $ruleContext->addElement('passengerIsSilverCardHolder', false);
        $p = $rule->evaluate($ruleContext);

        $this->assertFalse($p->value);
    }

    public function testIsEligibleForUpgradePassengerCarryOnBaggageTooHeavy()
    {
        $rule = $this->getRuleIsEligibleForUpgrade();
        $ruleContext = $this->getRuleContextIsEligibleForUpgrade();
        $ruleContext->addElement('passengerCarryOnBaggageWeight', 15.1);
        $p = $rule->evaluate($ruleContext);

        $this->assertFalse($p->value);
    }

    private function getRuleIsEligibleForUpgrade()
    {
        $rule = new Rule('IsEligibleForUpgrade');
        $rule->addProposition('passengerIsEconomy');
        $rule->addProposition('passengerIsGoldCardHolder');
        $rule->addProposition('passengerIsSilverCardHolder');
        $rule->addOperator(Logical::LOGICAL_OR);
        $rule->addOperator(Logical::LOGICAL_AND);
        $rule->addVariable('passengerCarryOnBaggageWeight');
        $rule->addVariable('passengerCarryOnBaggageAllowance');
        $rule->addOperator(Comparison::LESS_THAN_OR_EQUAL_TO);
        $rule->addOperator(Logical::LOGICAL_AND);

        return $rule;
    }

    private function getRuleContextIsEligibleForUpgrade()
    {
        $ruleContext = new RuleContext();
        $ruleContext->addElement('passengerIsEconomy', true);
        $ruleContext->addElement('passengerIsGoldCardHolder', true);
        $ruleContext->addElement('passengerIsSilverCardHolder', true);
        $ruleContext->addElement('passengerCarryOnBaggageAllowance', 15.0);
        $ruleContext->addElement('passengerCarryOnBaggageWeight', 10.0);

        return $ruleContext;
    }

}
