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
        $document->url = "https://example.com/1";
        $document->campaignId = 1;
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
        $expected = "https://example.com/1";
        $this->testCreateCampaignDocument();
        $document = CampaignDocument::find()->one();
        $actual = $document->url;
        $this->assertEquals($expected, $actual);
        $expected = "https://example.com/2";
        $document->url = $expected;
        $document->save();
        $test = CampaignDocument::find()->one();
        $actual = $test->url;
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
