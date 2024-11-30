<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Document;

class DocumentTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateDocument()
    {
        $now = time();
        $document = new Document();
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

    public function testReadDocument()
    {
        $this->testCreateDocument();
        $document = Document::find()->one();
        $this->assertNotEmpty($document);
    }

    public function testUpdateDocument()
    {
        $expected = 1;
        $this->testCreateDocument();
        $document = Document::find()->one();
        $actual = $document->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $document->owner = 2;
        $document->save();
        $test = Document::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteDocument()
    {
        $this->testCreateDocument();
        $document = Document::find()->one();
        $this->assertNotEmpty($document);
        $document->delete();
        $document = Document::find()->one();
        $this->assertEmpty($document);
    }
}
