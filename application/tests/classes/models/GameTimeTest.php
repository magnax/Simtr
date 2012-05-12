<?php defined('SYSPATH') or die('No direct script access');

include_once APPPATH.'classes/model/GameTime.php';

class GameTimeTest extends PHPUnit_Framework_TestCase {
  
    protected $gametime = null;
    
    public function setUp() {
        $this->gametime = new Model_GameTime();
    }
    
    public function testModelLoaded() {
        $this->assertNotNull($this->gametime);
    }
    
    public function testGetRawTime() {
        $rawTime = $this->gametime->getRawTime();
        $this->assertType('int', $rawTime);
        $this->assertGreaterThan(0, $rawTime);
    }
    
    /**
     * @expectedException BadDaemonException
     */
    public function testGetRawTimeExceptionBadDaemon() {
        
        //bad daemon
        $rawTime = $this->gametime->getRawTime('/usr/local/lib/simtr/d_bad.py');

    }
    
    /**
     * @expectedException DaemonNotRunningException
     */
    public function testGetRawTimeExceptionDaemonNotRunning() {
        
        //not running daemon
        $rawTime = $this->gametime->getRawTime('/usr/local/lib/simtr/d_test.py');

    }
    
    public function testGetTime() {
        $t = $this->gametime->getTime();
        $this->assertType('string', $t);
        $this->assertRegExp('/[0-9]{1,2}:[0-9]{2}:[0-9]{2}/', $t);
    }
    
    public function testGetDate() {
        $t = $this->gametime->getDate();
        $this->assertType('string', $t);
        $this->assertRegExp('/[0-9]{1,4}-([0-1][0-9])|[0-9]/', $t);
    }
 
    public function testGetDateTime() {
        $t = $this->gametime->getDateTime();
        $this->assertType('string', $t);
        $this->assertRegExp('/[0-9]{1,4}-([0-1][0-9])|[0-9] [0-9]{1,2}:[0-9]{2}:[0-9]{2}/', $t);
    }
    
    public function testDecodeRawTime() {
        $rawtime = 2116726; //
        $timeArray = $this->gametime->decodeRawTime($rawtime);
        $this->assertEquals(46, $timeArray['s']); //seconds
        $this->assertEquals(58, $timeArray['m']); //minutes
        $this->assertEquals(11, $timeArray['h']); //hours
        $this->assertEquals(24, $timeArray['d']); //total days
        $this->assertEquals(1, $timeArray['y']); //years
        $this->assertEquals(4, $timeArray['f']); //days of year

    }
    
    public function testFormatDateTime() {
        $year = 1728000; //seconds
        $day = 86400; //seconds
        $hour = 3600;
        $minute = 60;
        
        $d = array(0,0,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:0:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,0,0,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:0:59', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,0,1,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:1:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,2,59,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 2:59:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,1,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-1 0:0:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,23,59,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 23:59:59', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,19,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-19 0:0:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,19,23,59,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-19 23:59:59', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(1,0,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('1-0 0:0:0', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(1,4,12,45,3);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('1-4 12:45:3', $this->gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(112,14,5,7,54);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('2254 5:7:54', $this->gametime->formatDateTime($rt, 'd h:m:s'));
        
    }
    
}

?>
