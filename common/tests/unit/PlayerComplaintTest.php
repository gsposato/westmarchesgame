<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\PlayerComplaint;

class PlayerComplaintTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreatePlayerComplaint()
    {
        $now = time();
        $complaint = new PlayerComplaint();
        $complaint->campaignId = 1;
        $complaint->reportingUserId = 1;
        $complaint->reportingCharacterId = 1;
        $complaint->offendingUserId = 1;
        $complaint->offendingCharacterId = 1;
        $complaint->note = "Test";
        $complaint->owner = 1;
        $complaint->creator = 1;
        $complaint->created = $now;
        $complaint->updated = $now;
        $isSaved = $complaint->save();
        $this->assertTrue($isSaved);
        $hasErrors = $complaint->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadPlayerComplaint()
    {
        $this->testCreatePlayerComplaint();
        $complaint = PlayerComplaint::find()->one();
        $this->assertNotEmpty($complaint);
    }

    public function testUpdatePlayerComplaint()
    {
        $expected = 1;
        $this->testCreatePlayerComplaint();
        $complaint = PlayerComplaint::find()->one();
        $actual = $complaint->owner;
        $this->assertEquals($expected, $actual);
        $expected = 2;
        $complaint->owner = 2;
        $complaint->save();
        $test = PlayerComplaint::find()->one();
        $actual = $test->owner;
        $this->assertEquals($expected, $actual);
    }

    public function testDeletePlayerComplaint()
    {
        $this->testCreatePlayerComplaint();
        $complaint = PlayerComplaint::find()->one();
        $this->assertNotEmpty($complaint);
        $complaint->delete();
        $complaint = PlayerComplaint::find()->one();
        $this->assertEmpty($complaint);
    }
}
