<?php defined('SYSPATH') or die('No direct access allowed!');
 
class EnvironmentTest extends Kohana_UnitTest_TestCase
{
    public function testWeTestingProperRedisDatabase() {
        $this->assertEquals('testing', RedisDB::instance()->get('database_name'));
    }
}