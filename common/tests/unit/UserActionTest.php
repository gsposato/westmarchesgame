<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\UserAction;

class UserActionTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateUserAction()
    {
        $now = time();
        $userAction = new UserAction();
        $userAction->userId = 1;
        $userAction->uri = "/example";
        $userAction->unixtime = $now;
        $isSaved = $userAction->save();
        $this->assertTrue($isSaved);
        $hasErrors = $userAction->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadUserAction()
    {
        $this->testCreateUserAction();
        $userAction = UserAction::find()->one();
        $this->assertNotEmpty($userAction);
    }

    public function testUpdateUserAction()
    {
        $expected = "/example";
        $this->testCreateUserAction();
        $userAction = UserAction::find()->one();
        $actual = $userAction->uri;
        $this->assertEquals($expected, $actual);
        $expected = "/example-2";
        $userAction->uri = $expected;
        $userAction->save();
        $test = UserAction::find()->one();
        $actual = $test->uri;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteUserAction()
    {
        $this->testCreateUserAction();
        $userAction = UserAction::find()->one();
        $this->assertNotEmpty($userAction);
        $userAction->delete();
        $userAction = UserAction::find()->one();
        $this->assertEmpty($userAction);
    }
}
