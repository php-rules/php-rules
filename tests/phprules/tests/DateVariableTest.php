<?php
namespace phprules\tests;

use phprules\DateVariable;

class DateVariableTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->date = new DateVariable('dateNow','now');
    }

    public function testConstructor()
    {
        $this->assertNotNull($this->date);
    }

    public function test_DateVariable_equalTo()
    {
        $now = new DateVariable('dateNow', 'now');
        $p = $this->date->equalTo($now);
        $this->assertTrue($p->value);
    }

    public function test_DateVariable_notEqualTo()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $p = $this->date->notEqualTo($then);
        $this->assertTrue($p->value);
        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $p = $this->date->notEqualTo($lastWednesday);
        $this->assertTrue($p->value);
    }

    public function test_DateVariable_lessThan()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $p = $then->lessThan($this->date);
        $this->assertTrue($p->value);
        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $p = $lastWednesday->lessThan($this->date);
        $this->assertTrue($p->value);
    }

    public function test_DateVariable_lessThanOrEqualTo()
    {
        $now = new DateVariable('dateNow', 'now');
        $p = $this->date->lessThanOrEqualTo($now);
        $then = new DateVariable('dateThen', '2011-01-01');
        $p = $then->lessThanOrEqualTo($this->date);
        $this->assertTrue($p->value);
        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $p = $lastWednesday->lessThanOrEqualTo($this->date);
        $this->assertTrue($p->value);
    }

    public function test_DateVariable_greaterThan ()
    {
        $then = new DateVariable('dateThen', '2011-01-01');
        $p = $this->date->greaterThan ($then);
        $this->assertTrue($p->value);
        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $p = $this->date->greaterThan ($lastWednesday);
        $this->assertTrue($p->value);
    }

    public function test_DateVariable_greaterThanOrEqualTo ()
    {
        $now = new DateVariable('dateNow', 'now');
        $p = $now->greaterThanOrEqualTo ($this->date);
        $then = new DateVariable('dateThen', '2011-01-01');
        $p = $this->date->greaterThanOrEqualTo ($then);
        $this->assertTrue($p->value);
        $lastWednesday = new DateVariable('lastWednesday', 'last lastWednesday');
        $p = $this->date->greaterThanOrEqualTo ($lastWednesday);
        $this->assertTrue($p->value);
    }
}
