<?php defined('SYSPATH') or die('No direct script access ST.');
 
class SampleTest extends PHPUnit_Framework_TestCase {
    
    /**
     *
     * @var Redis Database Object
     */
    protected $redis;
    
    protected function setUp() {
        
        $this->redis = new Predis_Client(array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 1,
            'alias' => 'mn_test'
        ));

        try {
            $this->redis->flushdb();
        } catch (Predis_CommunicationException $e) {
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
    
    public function testEqual() {
        
        $this->assertEquals(2, (1+1));
        
    }
    
    public function tearDown() {
        //unconnect db or sth.
    }
    
}
