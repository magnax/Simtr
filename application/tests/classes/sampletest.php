<?php defined('SYSPATH') or die('No direct script access ST.');
 
class SampleTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var Redis Database Object
     */
    protected $redis;
    
    protected function setUp() {
        
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1:6379');
        $this->redis->select(1);

        try {
            $this->redis->flushdb();
        } catch (RedisException $e) {
            $this->redirectError('Redis server down?');
        }
        
    }

    public function testEmptyDb() {
        
        $keys = $this->redis->dbsize();
        $this->assertEquals(0, $keys);
        
    }
    
    public function testRedisSet() {
        
        $this->redis->set('testvalue', 666);
        $keys = $this->redis->dbsize();
        $this->assertEquals(1, $keys);
        
        $v = $this->redis->get('testvalue');
        $this->assertEquals(666, $v);
        
    }
    
}
