<?php

namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\CampaignPlayer;

class CampaignPlayerTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCampaignPlayer()
    {
        $now = time();
        $player = new CampaignPlayer();
        $player->campaignId = 1;
        $player->userId = 1;
        $player->isPlayer = 1;
        $player->isHost = 1;
        $player->isAdmin = 1;
        $player->created = $now;
        $player->updated = $now;
        $isSaved = $player->save();
        $this->assertTrue($isSaved);
        $hasErrors = $player->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadCampaignPlayer()
    {
        $this->testCreateCampaignPlayer();
        $player = CampaignPlayer::find()->one();
        $this->assertNotEmpty($player);
    }

    public function testUpdateCampaignPlayer()
    {
        $expected = 1;
        $this->testCreateCampaignPlayer();
        $player = CampaignPlayer::find()->one();
        $actual = $player->isAdmin;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $player->isAdmin = 2;
        $player->save();
        $test = CampaignPlayer::find()->one();
        $actual = $test->isAdmin;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCampaignPlayer()
    {
        $this->testCreateCampaignPlayer();
        $player = CampaignPlayer::find()->one();
        $this->assertNotEmpty($player);
        $player->delete();
        $player = CampaignPlayer::find()->one();
        $this->assertNotEmpty($player);
    }
}
