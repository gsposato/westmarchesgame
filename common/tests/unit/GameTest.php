<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Game;

class GameTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGame()
    {
        $now = time();
        $game = new Game();
        $game->name = "Test";
        $game->campaignId = 1;
        $game->timeDuration = "4-5 hours";
        $isSaved = $game->save();
        $this->assertTrue($isSaved);
        $hasErrors = $game->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGame()
    {
        $this->testCreateGame();
        $game = Game::find()->one();
        $this->assertNotEmpty($game);
    }

    public function testUpdateGame()
    {
        $expected = "Test";
        $this->testCreateGame();
        $game = Game::find()->one();
        $actual = $game->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $game->name = $expected;
        $game->save();
        $test = Game::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGame()
    {
        $this->testCreateGame();
        $game = Game::find()->one();
        $this->assertNotEmpty($game);
        $game->delete();
        $game = Game::find()->one();
        $this->assertNotEmpty($game);
    }

    public function testDurationInSeconds()
    {
        $this->testCreateGame();
        $game = Game::find()->one();
        $expected = 5 * 60 * 60;
        $actual = $game->durationInSeconds();
        $this->assertEquals($expected, $actual);
    }
}
