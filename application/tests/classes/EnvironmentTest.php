<?php
 
class EnvironmentTest extends PHPUnit_Framework_TestCase {
    
    public function testWeTestingProperRedisDatabase() {
        $this->assertEquals('testing', RedisDB::instance()->get('database_name'));
    }
    
    public function testWeTestingProperMySqlDatabase() {
        $q = DB::query(Database::SELECT, "select database() as name");
        $result = $q->execute();
        $this->assertEquals('simtr_test', $result->get('name'));
    }
    
}