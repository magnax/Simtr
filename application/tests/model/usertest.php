<?php defined('SYSPATH') or die('No direct script access ST.');

//include_once APPPATH.'classes/model/User.php';

class UserTest extends PHPUnit_Framework_TestCase {
    
    protected $user;
    private $redis = null;
    private static $_redis = null;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$_redis = new Redis();
        self::$_redis->connect('127.0.0.1:6379');
        self::$_redis->select(1);
        
        self::$_redis->flushdb();
        
    }


    public function setUp() {
        
        $this->redis = self::$_redis;
        
        $this->user = Model_User::getInstance($this->redis);
        
    }

    public function testIsInstanceOfUser() {
        $this->assertNull(Model_User::getInstance(null));
        $this->assertTrue(Model_User::getInstance($this->redis) instanceof Model_User_Redis);
    }
    
    public function testCreateAndSaveUser() {
        
        $post = array(
            'email'=>'test@test.com', 
            'pass'=>'password'
        );
        
        $this->user->createNew($post);
        $this->assertEquals('test@test.com', $this->user->getEmail());
        $this->assertEquals(1, $this->user->getID());
        $this->assertEquals(19, strlen($this->user->getRegisterDate()));
        $this->assertEquals(Model_User::STATUS_INACTIVE, $this->user->getStatus());
        $this->user->setStatus(Model_User::STATUS_ACTIVE);
        $this->assertEquals(Model_User::STATUS_ACTIVE, $this->user->getStatus());
        
        $this->user->save();
        
        
        $this->assertTrue($this->user->isDuplicateEmail('test@test.com'));
        $this->assertFalse($this->user->isDuplicateEmail('test1@test.com'));
        
    }
    
    public function testLoginUser() {
        
        $this->assertNull($this->user->login('fake@email.com', 'notcorrect'));
        $this->assertEquals(32, strlen($this->user->login('test@test.com', 'password')));
        $this->assertTrue($this->user->isLoggedIn());
        
    }
    
    public function testUpdateUser() {
        
        $newUserData = array(
            'firstname'=>'Jan',
            'lastname'=>'Kowalski',
            'birthdate'=>'1956-09-20'
        );
        
        $this->user->update($newUserData);
        
        $this->assertEquals('Jan Kowalski', $this->user->getFullName());
        $this->assertEquals(1956, $this->user->getBirthYear());
        
    }
    
}

?>
 