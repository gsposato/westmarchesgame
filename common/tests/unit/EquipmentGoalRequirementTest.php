<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\EquipmentGoalRequirement;

class EquipmentGoalRequirementTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateEquipmentGoalReq()
    {
        $now = time();
        $req = new EquipmentGoalRequirement();
        $req->name = "Test";
        $req->campaignId = 1;
        $req->equipmentGoalId = 1;
        $req->description = "test";
        $req->progress = "100";
        $isSaved = $req->save();
        $this->assertTrue($isSaved);
        $hasErrors = $req->getErrors();
        $this->assertEmpty($hasErrors);

    }
    public function testReadEquipmentGoalReq()
    {
        $this->testCreateEquipmentGoalReq();
        $req = EquipmentGoalRequirement::find()->one();
        $this->assertNotEmpty($req);
    }

    public function testUpdateEquipmentGoalReq()
    {
        $expected = "Test";
        $this->testCreateEquipmentGoalReq();
        $req = EquipmentGoalRequirement::find()->one();
        $actual = $req->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $req->name = $expected;
        $req->save();
        $test = EquipmentGoalRequirement::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteEquipmentGoalReq()
    {
        $this->testCreateEquipmentGoalReq();
        $req = EquipmentGoalRequirement::find()->one();
        $this->assertNotEmpty($req);
        $req->delete();
        $req = EquipmentGoalRequirement::find()->one();
        $this->assertEmpty($req);
    }
}
