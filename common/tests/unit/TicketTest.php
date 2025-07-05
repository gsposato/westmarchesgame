<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Ticket;

class TicketTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateTicket()
    {
        $now = time();
        $ticket = new Ticket();
        $ticket->name = "Test";
        $ticket->status = 1;
        $ticket->note = "Test";
        $ticket->campaignId = 1;
        $isSaved = $ticket->save();
        $this->assertTrue($isSaved);
        $hasErrors = $ticket->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadTicket()
    {
        $this->testCreateTicket();
        $ticket = Ticket::find()->one();
        $this->assertNotEmpty($ticket);
    }

    public function testUpdateTicket()
    {
        $expected = "Test";
        $this->testCreateTicket();
        $ticket = Ticket::find()->one();
        $actual = $ticket->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $ticket->name = $expected;
        $ticket->save();
        $test = Ticket::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteTicket()
    {
        $this->testCreateTicket();
        $ticket = Ticket::find()->one();
        $this->assertNotEmpty($ticket);
        $ticket->delete();
        $ticket = Ticket::find()->one();
        $this->assertNotEmpty($ticket);
    }
}
