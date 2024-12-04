<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\CampaignDocument;

class CampaignDocumentTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCampaignDocument()
    {
        $now = time();
        $document = new CampaignDocument();
        $document->name = "Test";
        $document->url = "https://example.com";
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

    public function testReadCampaignDocument()
    {
        $this->testCreateCampaignDocument();
        $document = CampaignDocument::find()->one();
        $this->assertNotEmpty($document);
    }

    public function testUpdateCampaignDocument()
    {
        $expected = 1;
        $this->testCreateCampaignDocument();
        $document = CampaignDocument::find()->one();
        $actual = $document->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $document->owner = 2;
        $document->save();
        $test = CampaignDocument::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCampaignDocument()
    {
        $this->testCreateCampaignDocument();
        $document = CampaignDocument::find()->one();
        $this->assertNotEmpty($document);
        $document->delete();
        $document = CampaignDocument::find()->one();
        $this->assertEmpty($document);
    }
}
