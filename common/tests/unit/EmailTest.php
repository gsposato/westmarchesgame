<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Email;

class EmailTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
        $email = Email::find()->all();
        if (!empty($email)) {
            foreach ($email as $model) {
                $model->delete();
            }
        }
    }

    // tests
    public function testCreateEmail()
    {
        $now = time();
        $email = new Email();
        $email->name = "Test";
        $email->result = "sent";
        $email->response = 1;
        $email->owner = 0; // system
        $email->creator = 0; // system
        $email->created = $now;
        $email->updated = $now;
        $email->deleted = $now;
        $isSaved = $email->save();
        $this->assertTrue($isSaved);
        $hasErrors = $email->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadEmail()
    {
        $this->testCreateEmail();
        $email = Email::find()->one();
        $this->assertNotEmpty($email);
    }

    public function testUpdateEmail()
    {
        $expected = "Test";
        $this->testCreateEmail();
        $email = Email::find()->one();
        $actual = $email->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $email->name = $expected;
        $email->save();
        $test = Email::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteEmail()
    {
        $this->testCreateEmail();
        $email = Email::find()->one();
        $this->assertNotEmpty($email);
        $email->delete();
        $email = Email::find()->one();
        $this->assertEmpty($email);
    }
}
