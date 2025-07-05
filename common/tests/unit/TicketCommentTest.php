<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\TicketComment;

class TicketCommentTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateTicketComment()
    {
        $now = time();
        $comment = new TicketComment();
        $comment->note = "Test";
        $comment->ticketId = 1;
        $comment->campaignId = 1;
        $isSaved = $comment->save();
        $this->assertTrue($isSaved);
        $hasErrors = $comment->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadTicketComment()
    {
        $this->testCreateTicketComment();
        $comment = TicketComment::find()->one();
        $this->assertNotEmpty($comment);
    }

    public function testUpdateTicketComment()
    {
        $expected = "Test";
        $this->testCreateTicketComment();
        $comment = TicketComment::find()->one();
        $actual = $comment->note;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $comment->note = $expected;
        $comment->save();
        $test = TicketComment::find()->one();
        $actual = $test->note;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteTicketComment()
    {
        $this->testCreateTicketComment();
        $comment = TicketComment::find()->one();
        $this->assertNotEmpty($comment);
        $comment->delete();
        $comment = TicketComment::find()->one();
        $this->assertNotEmpty($comment);
    }
}
