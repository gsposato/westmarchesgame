<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\CampaignCharacter;

class CampaignCharacterTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCampaignCharacter()
    {
        $now = time();
        $char = new CampaignCharacter();
        $char->name = "Test";
        $char->campaignId = 1;
        $char->slot = 1;
        $char->status = 1;
        $char->owner = 1;
        $char->creator = 1;
        $char->created = $now;
        $char->updated = $now;
        $isSaved = $char->save();
        $this->assertTrue($isSaved);
        $hasErrors = $char->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadCampaignCharacter()
    {
        $this->testCreateCampaignCharacter();
        $char = CampaignCharacter::find()->one();
        $this->assertNotEmpty($char);
    }

    public function testUpdateCampaignCharacter()
    {
        $expected = 1;
        $this->testCreateCampaignCharacter();
        $char = CampaignCharacter::find()->one();
        $actual = $char->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $char->owner = 2;
        $char->save();
        $test = CampaignCharacter::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCampaignCharacter()
    {
        $this->testCreateCampaignCharacter();
        $char = CampaignCharacter::find()->one();
        $this->assertNotEmpty($char);
        $char->delete();
        $char = CampaignCharacter::find()->one();
        $this->assertEmpty($char);
    }
}
