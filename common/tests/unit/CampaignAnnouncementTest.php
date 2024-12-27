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
        $document = new CampaignAnnouncement();
        $document->name = "Test";
        $document->note = "example note";
        $document->campaignId = 1;
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
        $expected = "example note";
        $this->testCreateCampaignAnnouncement();
        $document = CampaignAnnouncement::find()->one();
        $actual = $document->note;
        $this->assertEquals($expected, $actual);
        $expected = "example note 2";
        $document->note = $expected;
        $document->save();
        $test = CampaignAnnouncement::find()->one();
        $actual = $test->note;
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
