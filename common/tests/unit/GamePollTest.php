<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GamePoll;

class GamePollTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGamePoll()
    {
        $now = time();
        $gamePoll = new GamePoll();
        $gamePoll->note = "Test";
        $gamePoll->gameId = 1;
        $gamePoll->owner = 1;
        $gamePoll->creator = 1;
        $gamePoll->created = $now;
        $gamePoll->updated = $now;
        $isSaved = $gamePoll->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gamePoll->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGamePoll()
    {
        $this->testCreateGamePoll();
        $gamePoll = GamePoll::find()->one();
        $this->assertNotEmpty($gamePoll);
    }

    public function testUpdateGamePoll()
    {
        $expected = 1;
        $this->testCreateGamePoll();
        $gamePoll = GamePoll::find()->one();
        $actual = $gamePoll->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $gamePoll->owner = 2;
        $gamePoll->save();
        $test = GamePoll::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGamePoll()
    {
        $this->testCreateGamePoll();
        $gamePoll = GamePoll::find()->one();
        $this->assertNotEmpty($gamePoll);
        $gamePoll->delete();
        $gamePoll = GamePoll::find()->one();
        $this->assertEmpty($gamePoll);
    }
}
