<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Form;

class FormTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateForm()
    {
        $now = time();
        $form = new Form();
        $form->name = "Test";
        $form->status = 1;
        $form->note = "Test";
        $form->campaignId = 1;
        $isSaved = $form->save();
        $this->assertTrue($isSaved);
        $hasErrors = $form->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadForm()
    {
        $this->testCreateForm();
        $form = Form::find()->one();
        $this->assertNotEmpty($form);
    }

    public function testUpdateForm()
    {
        $expected = "Test";
        $this->testCreateForm();
        $form = Form::find()->one();
        $actual = $form->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $form->name = $expected;
        $form->save();
        $test = Form::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteForm()
    {
        $this->testCreateForm();
        $form = Form::find()->one();
        $this->assertNotEmpty($form);
        $form->delete();
        $form = Form::find()->one();
        $this->assertNotEmpty($form);
    }
}
