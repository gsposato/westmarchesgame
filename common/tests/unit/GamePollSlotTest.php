<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GamePollSlot;

class GamePollSlotTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGamePollSlot()
    {
        $tz = "America/New_York";
        $now = time();
        $humantime = date("Y-m-d H:i:s");
        $gamePollSlot = new GamePollSlot();
        $gamePollSlot->gamePollId = 1;
        $gamePollSlot->humantime = $humantime;
        $gamePollSlot->timezone = $tz;
        $gamePollSlot->makeUnixTime();
        $isSaved = $gamePollSlot->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gamePollSlot->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGamePollSlot()
    {
        $this->testCreateGamePollSlot();
        $gamePollSlot = GamePollSlot::find()->one();
        $this->assertNotEmpty($gamePollSlot);
    }

    public function testUpdateGamePollSlot()
    {
        $expected = "America/New_York";
        $this->testCreateGamePollSlot();
        $gamePollSlot = GamePollSlot::find()->one();
        $actual = $gamePollSlot->timezone;
        $this->assertEquals($expected, $actual);
        $expected = "America/Chicago";
        $gamePollSlot->timezone = $expected;
        $gamePollSlot->save();
        $test = GamePollSlot::find()->one();
        $actual = $test->timezone;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGamePollSlot()
    {
        $this->testCreateGamePollSlot();
        $gamePollSlot = GamePollSlot::find()->one();
        $this->assertNotEmpty($gamePollSlot);
        $gamePollSlot->delete();
        $gamePollSlot = GamePollSlot::find()->one();
        $this->assertEmpty($gamePollSlot);
    }
}
