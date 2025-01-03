<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Campaign;

class CampaignTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCampaign()
    {
        $now = time();
        $campaign = new Campaign();
        $campaign->name = "Test";
        $isSaved = $campaign->save();
        $this->assertTrue($isSaved);
        $hasErrors = $campaign->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadCampaign()
    {
        $this->testCreateCampaign();
        $campaign = Campaign::find()->one();
        $this->assertNotEmpty($campaign);
    }

    public function testUpdateCampaign()
    {
        $expected = "Test";
        $this->testCreateCampaign();
        $campaign = Campaign::find()->one();
        $actual = $campaign->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $campaign->name = $expected;
        $campaign->save();
        $test = Campaign::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCampaign()
    {
        $this->testCreateCampaign();
        $campaign = Campaign::find()->one();
        $this->assertNotEmpty($campaign);
        $campaign->delete();
        $campaign = Campaign::find()->one();
        $this->assertEmpty($campaign);
    }

    public function testGetName()
    {
        $this->testCreateCampaign();
    }
}
