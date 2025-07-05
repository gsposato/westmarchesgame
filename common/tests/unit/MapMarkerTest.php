<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\MapMarker;

class MapMarkerTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateMapMarker()
    {
        $now = time();
        $mapMarker = new MapMarker();
        $mapMarker->name = "Test";
        $mapMarker->campaignId = 1;
        $mapMarker->mapId = 1;
        $mapMarker->color = "red";
        $mapMarker->lat = 1.1;
        $mapMarker->lng = 2.2;
        $isSaved = $mapMarker->save();
        $this->assertTrue($isSaved);
        $hasErrors = $mapMarker->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadMapMarker()
    {
        $this->testCreateMapMarker();
        $mapMarker = MapMarker::find()->one();
        $this->assertNotEmpty($mapMarker);
    }

    public function testUpdateMapMarker()
    {
        $expected = "Test";
        $this->testCreateMapMarker();
        $mapMarker = MapMarker::find()->one();
        $actual = $mapMarker->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $mapMarker->name = $expected;
        $mapMarker->save();
        $test = MapMarker::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteMapMarker()
    {
        $this->testCreateMapMarker();
        $mapMarker = MapMarker::find()->one();
        $this->assertNotEmpty($mapMarker);
        $mapMarker->delete();
        $mapMarker = MapMarker::find()->one();
        $this->assertNotEmpty($mapMarker);
    }
}
