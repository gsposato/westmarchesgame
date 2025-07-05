<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Equipment;

class EquipmentTest extends \Codeception\Test\Unit
{
    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateEquipment()
    {
        $now = time();
        $equipment = new Equipment();
        $equipment->name = "Test";
        $equipment->campaignId = 1;
        $equipment->characterId = 1;
        $equipment->category = 1;
        $equipment->state = 1;
        $equipment->description = "test";
        $isSaved = $equipment->save();
        $this->assertTrue($isSaved);
        $hasErrors = $equipment->getErrors();
        $this->assertEmpty($hasErrors);

    }
    public function testReadEquipment()
    {
        $this->testCreateEquipment();
        $equipment = Equipment::find()->one();
        $this->assertNotEmpty($equipment);
    }

    public function testUpdateEquipment()
    {
        $expected = "Test";
        $this->testCreateEquipment();
        $equipment = Equipment::find()->one();
        $actual = $equipment->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $equipment->name = $expected;
        $equipment->save();
        $test = Equipment::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteEquipment()
    {
        $this->testCreateEquipment();
        $equipment = Equipment::find()->one();
        $this->assertNotEmpty($equipment);
        $equipment->delete();
        $equipment = Equipment::find()->one();
        $this->assertNotEmpty($equipment);
    }

}
