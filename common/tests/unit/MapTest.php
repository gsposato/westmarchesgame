<?php


namespace common\tests\Unit;

use common\tests\UnitTester;
use common\models\Map;

class MapTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testCreateMap()
    {
        $now = time();
        $map = new Map();
        $map->name = "Test";
        $map->campaignId = 1;
        $map->image = "/tmp/test.png";
        $map->minzoom = 0;
        $map->maxzoom = 0;
        $map->defaultzoom = 0;
        $isSaved = $map->save();
        $this->assertTrue($isSaved);
        $hasErrors = $map->getErrors();
        $this->assertEmpty($hasErrors);
    }

    public function testReadMap()
    {
        $this->testCreateMap();
        $map = Map::find()->one();
        $this->assertNotEmpty($map);
    }

    public function testUpdateMap()
    {
        $expected = "Test";
        $this->testCreateMap();
        $map = Map::find()->one();
        $actual = $map->name;
        $this->assertEquals($expected, $actual);
        $expected = "Test2";
        $map->name = $expected;
        $map->save();
        $test = Map::find()->one();
        $actual = $test->name;
        $this->assertEquals($expected, $actual);
    }

    public function testDeleteMap()
    {
        $this->testCreateMap();
        $map = Map::find()->one();
        $this->assertNotEmpty($map);
        $map->delete();
        $map = Map::find()->one();
        $this->assertNotEmpty($map);
    }
}
