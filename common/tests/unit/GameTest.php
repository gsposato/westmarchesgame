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
        $game->owner = 1;
        $game->creator = 1;
        $game->created = $now;
        $game->updated = $now;
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
        $expected = 1;
        $this->testCreateGame();
        $game = Game::find()->one();
        $actual = $game->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $game->owner = 2;
        $game->save();
        $test = Game::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGame()
    {
        $this->testCreateGame();
        $game = Game::find()->one();
        $this->assertNotEmpty($game);
        $game->delete();
        $game = Game::find()->one();
        $this->assertEmpty($game);
    }
}
