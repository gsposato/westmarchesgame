<?php


namespace common\tests\Unit;

use common\models\Event;
use common\models\CampaignCharacter;
use common\tests\UnitTester;
use yii\helpers\StringHelper;

class EventTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
        $event = Event::find()->all();
        if (!empty($event)) {
            foreach ($event as $model) {
                $model->delete($hard = true);
            }
        }
    }

    // tests
    public function testCreateEvent()
    {
        $model = new CampaignCharacter();
        $model->id = 1;
        $model->name = "Test";
        $event = New Event();
        $event->modelId = $model->id;
        $event->modelClass = StringHelper::basename($model::class);
        $event->attributeName = "name";
        $event->attributeValue = strval($model->name);
        $event->owner = 1;
        $event->deleted = 0;
        $this->assertTrue($event->save());
        $hasErrors = $event->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadEvent()
    {
        $this->testCreateEvent();
        $event = Event::find()->one();
        $this->assertNotEmpty($event);
    }

    public function testUpdateEvent()
    {
        $expected = "CampaignCharacter";
        $this->testCreateEvent();
        $event = Event::find()->one();
        $actual = $event->modelClass;
        $this->assertEquals($expected, $actual);
        $expected = "Equipment";
        $event->modelClass = $expected;
        $event->save();
        $test = Event::find()->one();
        $actual = $test->modelClass;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteEvent()
    {
        $this->testCreateEvent();
        $event = Event::find()->one();
        $this->assertNotEmpty($event);
        $event->delete();
        $event = Event::find()->one();
        $this->assertNotEmpty($event);
    }
}
