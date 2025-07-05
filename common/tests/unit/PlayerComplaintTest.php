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
        $complaint->name = uniqId();
        $complaint->reportingPlayerId = 1;
        $complaint->reportingCharacterId = 1;
        $complaint->offendingPlayerId = 1;
        $complaint->offendingCharacterId = 1;
        $complaint->note = "Test";
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
        $expected = "Test";
        $this->testCreatePlayerComplaint();
        $complaint = PlayerComplaint::find()->one();
        $actual = $complaint->note;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $complaint->note = $expected;
        $complaint->save();
        $test = PlayerComplaint::find()->one();
        $actual = $test->note;
        $this->assertEquals($expected, $actual);
    }

    public function testDeletePlayerComplaint()
    {
        $this->testCreatePlayerComplaint();
        $complaint = PlayerComplaint::find()->one();
        $this->assertNotEmpty($complaint);
        $complaint->delete();
        $complaint = PlayerComplaint::find()->one();
        $this->assertNotEmpty($complaint);
    }
}
