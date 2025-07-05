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
        $expected = "Test";
        $this->testCreateGamePoll();
        $gamePoll = GamePoll::find()->one();
        $actual = $gamePoll->note;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $gamePoll->note = $expected;
        $gamePoll->save();
        $test = GamePoll::find()->one();
        $actual = $test->note;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGamePoll()
    {
        $this->testCreateGamePoll();
        $gamePoll = GamePoll::find()->one();
        $this->assertNotEmpty($gamePoll);
        $gamePoll->delete();
        $gamePoll = GamePoll::find()->one();
        $this->assertNotEmpty($gamePoll);
    }
}
