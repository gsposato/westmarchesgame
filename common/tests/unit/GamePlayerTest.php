<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\GamePlayer;

class GamePlayerTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateGamePlayer()
    {
        $now = time();
        $gamePlayer = new GamePlayer();
        $gamePlayer->gameId = 1;
        $gamePlayer->userId = 1;
        $gamePlayer->characterId = 1;
        $gamePlayer->status = 1;
        $gamePlayer->owner = 1;
        $gamePlayer->creator = 1;
        $gamePlayer->created = $now;
        $gamePlayer->updated = $now;
        $isSaved = $gamePlayer->save();
        $this->assertTrue($isSaved);
        $hasErrors = $gamePlayer->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadGamePlayer()
    {
        $this->testCreateGamePlayer();
        $gamePlayer = GamePlayer::find()->one();
        $this->assertNotEmpty($gamePlayer);
    }

    public function testUpdateGamePlayer()
    {
        $expected = 1;
        $this->testCreateGamePlayer();
        $gamePlayer = GamePlayer::find()->one();
        $actual = $gamePlayer->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $gamePlayer->owner = 2;
        $gamePlayer->save();
        $test = GamePlayer::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteGamePlayer()
    {
        $this->testCreateGamePlayer();
        $gamePlayer = GamePlayer::find()->one();
        $this->assertNotEmpty($gamePlayer);
        $gamePlayer->delete();
        $gamePlayer = GamePlayer::find()->one();
        $this->assertEmpty($gamePlayer);
    }
}
