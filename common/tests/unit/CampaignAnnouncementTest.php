<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\CampaignAnnouncement;

class CampaignAnnouncementTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCampaignAnnouncement()
    {
        $now = time();
        $document = new CampaignAnnouncement();
        $document->name = "Test";
        $document->note = "example note";
        $document->campaignId = 1;
        $document->owner = 1;
        $document->creator = 1;
        $document->created = $now;
        $document->updated = $now;
        $isSaved = $document->save();
        $this->assertTrue($isSaved);
        $hasErrors = $document->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadCampaignAnnouncement()
    {
        $this->testCreateCampaignAnnouncement();
        $document = CampaignAnnouncement::find()->one();
        $this->assertNotEmpty($document);
    }

    public function testUpdateCampaignAnnouncement()
    {
        $expected = 1;
        $this->testCreateCampaignAnnouncement();
        $document = CampaignAnnouncement::find()->one();
        $actual = $document->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $document->owner = 2;
        $document->save();
        $test = CampaignAnnouncement::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCampaignAnnouncement()
    {
        $this->testCreateCampaignAnnouncement();
        $document = CampaignAnnouncement::find()->one();
        $this->assertNotEmpty($document);
        $document->delete();
        $document = CampaignAnnouncement::find()->one();
        $this->assertEmpty($document);
    }
}
