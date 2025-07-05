<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\EquipmentGoal;

class EquipmentGoalTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateEquipmentGoal()
    {
        $now = time();
        $goal = new EquipmentGoal();
        $goal->name = "Test";
        $goal->campaignId = 1;
        $goal->equipmentId = 1;
        $goal->description = "test";
        $isSaved = $goal->save();
        $this->assertTrue($isSaved);
        $hasErrors = $goal->getErrors();
        $this->assertEmpty($hasErrors);

    }
    public function testReadEquipmentGoal()
    {
        $this->testCreateEquipmentGoal();
        $goal = EquipmentGoal::find()->one();
        $this->assertNotEmpty($goal);
    }

    public function testUpdateEquipmentGoal()
    {
        $expected = "Test";
        $this->testCreateEquipmentGoal();
        $goal = EquipmentGoal::find()->one();
        $actual = $goal->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $goal->name = $expected;
        $goal->save();
        $test = EquipmentGoal::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteEquipmentGoal()
    {
        $this->testCreateEquipmentGoal();
        $goal = EquipmentGoal::find()->one();
        $this->assertNotEmpty($goal);
        $goal->delete();
        $goal = EquipmentGoal::find()->one();
        $this->assertNotEmpty($goal);
    }
}
