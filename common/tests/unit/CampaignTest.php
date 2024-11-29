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
        $campaign->owner = 1;
        $campaign->creator = 1;
        $campaign->created = $now;
        $campaign->updated = $now;
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
        $expected = 1;
        $this->testCreateCampaign();
        $campaign = Campaign::find()->one();
        $actual = $campaign->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $campaign->owner = 2;
        $campaign->save();
        $test = Campaign::find()->one();
        $actual = $test->owner;
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
}
