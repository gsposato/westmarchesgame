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
        $expected = "Test";
        $this->testCreateGameSummary();
        $gameSummary = GameSummary::find()->one();
        $actual = $gameSummary->note;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $gameSummary->note = $expected;
        $gameSummary->save();
        $test = GameSummary::find()->one();
        $actual = $test->note;
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
