<?php defined('SYSPATH') or die('No direct script access ST.');

include_once APPPATH.'classes/model/User.php';

class UserTest extends PHPUnit_Framework_TestCase {
    
    protected $user;
    protected $redis;


    public function setUp() {
        
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
        $this->user = Model_User::getInstance($this->redis);
        
    }


    public function testCreateAndSaveUser() {
        
        $post = array(
            'email'=>'test@test.com', 
            'pass'=>'password'
        );
        
        $this->user->createNew($post);
        $this->assertEquals('test@test.com', $this->user->getEmail());
        $this->assertNull($this->user->getID());
        
        $this->user->save();
        
        $this->assertEquals(1, $this->user->getID());
        $this->assertEquals('test@test.com', $this->user->getEmail());
        $this->assertTrue($this->user->isDuplicateEmail('test@test.com'));
        $this->assertFalse($this->user->isDuplicateEmail('test1@test.com'));
        
    }
    
    public function testLoginUser() {
        
        $post = array(
            'email'=>'test@test.com', 
            'pass'=>'password'
        );
        
        $this->user->createNew($post);
        $this->user->save();
        
        $this->assertEquals(32, strlen($this->user->login(1, 'password')));
        $this->assertFalse($this->user->login(1, 'notcorrect'));
        
    }
    
}

?>
 