<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Currency;

class CurrencyTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateCurrency()
    {
        $now = time();
        $currency = new Currency();
        $currency->name = "Test";
        $currency->campaignId = 1;
        $currency->color = "#000";
        $currency->description = "test";
        $isSaved = $currency->save();
        $this->assertTrue($isSaved);
        $hasErrors = $currency->getErrors();
        $this->assertEmpty($hasErrors);

    }
    public function testReadCurrency()
    {
        $this->testCreateCurrency();
        $currency = Currency::find()->one();
        $this->assertNotEmpty($currency);
    }

    public function testUpdateCurrency()
    {
        $expected = "Test";
        $this->testCreateCurrency();
        $currency = Currency::find()->one();
        $actual = $currency->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $currency->name = $expected;
        $currency->save();
        $test = Currency::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteCurrency()
    {
        $this->testCreateCurrency();
        $currency = Currency::find()->one();
        $this->assertNotEmpty($currency);
        $currency->delete();
        $currency = Currency::find()->one();
        $this->assertNotEmpty($currency);
    }
}
