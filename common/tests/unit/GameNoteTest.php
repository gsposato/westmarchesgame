<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GameNote;

class GameNoteTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGameNote()
    {
        $now = time();
        $gameNote = new GameNote();
        $gameNote->note = "Test";
        $gameNote->gameId = 1;
        $gameNote->inGamePoll = 1;
        $gameNote->inGameEvent = 1;
        $gameNote->inGameSummary = 1;
        $isSaved = $gameNote->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gameNote->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGameNote()
    {
        $this->testCreateGameNote();
        $gameNote = GameNote::find()->one();
        $this->assertNotEmpty($gameNote);
    }

    public function testUpdateGameNote()
    {
        $expected = "Test";
        $this->testCreateGameNote();
        $gameNote = GameNote::find()->one();
        $actual = $gameNote->note;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $gameNote->note = $expected;
        $gameNote->save();
        $test = GameNote::find()->one();
        $actual = $test->note;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGameNote()
    {
        $this->testCreateGameNote();
        $gameNote = GameNote::find()->one();
        $this->assertNotEmpty($gameNote);
        $gameNote->delete();
        $gameNote = GameNote::find()->one();
        $this->assertEmpty($gameNote);
    }
}
