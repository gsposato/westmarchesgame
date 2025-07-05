<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\PlayerCredit;

class PlayerCreditTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreatePlayerCredit()
    {
        $now = time();
        $credit = new PlayerCredit();
        $credit->campaignId = 1;
        $credit->userId = 1;
        $credit->category = 1;
        $credit->amount = floatval(1);
        $credit->owner = 1;
        $credit->creator = 1;
        $credit->created = $now;
        $credit->updated = $now;
        $isSaved = $credit->save();
        $this->assertTrue($isSaved);
        $hasErrors = $credit->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadPlayerCredit()
    {
        $this->testCreatePlayerCredit();
        $credit = PlayerCredit::find()->one();
        $this->assertNotEmpty($credit);
    }

    public function testUpdatePlayerCredit()
    {
        $expected = 1;
        $this->testCreatePlayerCredit();
        $credit = PlayerCredit::find()->one();
        $actual = $credit->amount;
        $this->assertEquals($expected, $actual);
        $expected = 1.25;
        $credit->amount = $expected;
        $credit->save();
        $test = PlayerCredit::find()->one();
        $actual = $test->amount;
        $this->assertEquals($expected, $actual);
    }

    public function testDeletePlayerCredit()
    {
        $this->testCreatePlayerCredit();
        $credit = PlayerCredit::find()->one();
        $this->assertNotEmpty($credit);
        $credit->delete();
        $credit = PlayerCredit::find()->one();
        $this->assertNotEmpty($credit);
    }
}
