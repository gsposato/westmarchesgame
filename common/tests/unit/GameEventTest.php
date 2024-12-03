<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GameEvent;

class GameEventTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGameEvent()
    {
        $now = time();
        $gameEvent = new GameEvent();
        $gameEvent->note = "Test";
        $gameEvent->gameId = 1;
        $gameEvent->gamePollSlotId = 1;
        $gameEvent->owner = 1;
        $gameEvent->creator = 1;
        $gameEvent->created = $now;
        $gameEvent->updated = $now;
        $isSaved = $gameEvent->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gameEvent->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGameEvent()
    {
        $this->testCreateGameEvent();
        $gameEvent = GameEvent::find()->one();
        $this->assertNotEmpty($gameEvent);
    }

    public function testUpdateGameEvent()
    {
        $expected = 1;
        $this->testCreateGameEvent();
        $gameEvent = GameEvent::find()->one();
        $actual = $gameEvent->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $gameEvent->owner = 2;
        $gameEvent->save();
        $test = GameEvent::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGameEvent()
    {
        $this->testCreateGameEvent();
        $gameEvent = GameEvent::find()->one();
        $this->assertNotEmpty($gameEvent);
        $gameEvent->delete();
        $gameEvent = GameEvent::find()->one();
        $this->assertEmpty($gameEvent);
    }
}
