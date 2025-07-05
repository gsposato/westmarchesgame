<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Purchase;

class PurchaseTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreatePurchase()
    {
        $now = time();
        $purchase = new Purchase();
        $purchase->name = "Test";
        $purchase->campaignId = 1;
        $purchase->characterId = 1;
        $purchase->currency = 1;
        $purchase->price = 1;
        $isSaved = $purchase->save();
        $this->assertTrue($isSaved);
        $hasErrors = $purchase->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadPurchase()
    {
        $this->testCreatePurchase();
        $purchase = Purchase::find()->one();
        $this->assertNotEmpty($purchase);
    }

    public function testUpdatePurchase()
    {
        $expected = "Test";
        $this->testCreatePurchase();
        $purchase = Purchase::find()->one();
        $actual = $purchase->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $purchase->name = $expected;
        $purchase->save();
        $test = Purchase::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeletePurchase()
    {
        $this->testCreatePurchase();
        $purchase = Purchase::find()->one();
        $this->assertNotEmpty($purchase);
        $purchase->delete();
        $purchase = Purchase::find()->one();
        $this->assertNotEmpty($purchase);
    }
}
