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
        $gameNote->owner = 1;
        $gameNote->creator = 1;
        $gameNote->created = $now;
        $gameNote->updated = $now;
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
        $expected = 1;
        $this->testCreateGameNote();
        $gameNote = GameNote::find()->one();
        $actual = $gameNote->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $gameNote->owner = 2;
        $gameNote->save();
        $test = GameNote::find()->one();
        $actual = $test->owner;
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
