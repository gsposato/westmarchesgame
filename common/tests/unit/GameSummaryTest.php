<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GameSummary;

class GameSummaryTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGameSummary()
    {
        $now = time();
        $gameSummary = new GameSummary();
        $gameSummary->note = "Test";
        $gameSummary->gameId = 1;
        $gameSummary->owner = 1;
        $gameSummary->creator = 1;
        $gameSummary->created = $now;
        $gameSummary->updated = $now;
        $isSaved = $gameSummary->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gameSummary->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGameSummary()
    {
        $this->testCreateGameSummary();
        $gameSummary = GameSummary::find()->one();
        $this->assertNotEmpty($gameSummary);
    }

    public function testUpdateGameSummary()
    {
        $expected = 1;
        $this->testCreateGameSummary();
        $gameSummary = GameSummary::find()->one();
        $actual = $gameSummary->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $gameSummary->owner = 2;
        $gameSummary->save();
        $test = GameSummary::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGameSummary()
    {
        $this->testCreateGameSummary();
        $gameSummary = GameSummary::find()->one();
        $this->assertNotEmpty($gameSummary);
        $gameSummary->delete();
        $gameSummary = GameSummary::find()->one();
        $this->assertEmpty($gameSummary);
    }
}
