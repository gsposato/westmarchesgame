<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\PlayerTrigger;

class PlayerTriggerTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreatePlayerTrigger()
    {
        $now = time();
        $trigger = new PlayerTrigger();
        $trigger->campaignId = 1;
        $trigger->playerId = 1;
        $trigger->name = "Test";
        $trigger->category = 1;
        $trigger->description = "test desc";
        $isSaved = $trigger->save();
        $this->assertTrue($isSaved);
        $hasErrors = $trigger->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadPlayerTrigger()
    {
        $this->testCreatePlayerTrigger();
        $trigger = PlayerTrigger::find()->one();
        $this->assertNotEmpty($trigger);
    }

    public function testUpdatePlayerTrigger()
    {
        $expected = "Test";
        $this->testCreatePlayerTrigger();
        $trigger = PlayerTrigger::find()->one();
        $actual = $trigger->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $trigger->name = $expected;
        $trigger->save();
        $test = PlayerTrigger::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeletePlayerTrigger()
    {
        $this->testCreatePlayerTrigger();
        $trigger = PlayerTrigger::find()->one();
        $this->assertNotEmpty($trigger);
        $trigger->delete();
        $trigger = PlayerTrigger::find()->one();
        $this->assertEmpty($trigger);
    }
}
