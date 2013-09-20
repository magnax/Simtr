<?php defined('SYSPATH') or die('No direct script access');

class Model_GameTimeTest extends PHPUnit_Framework_TestCase {
    
    public function testAdd() {
        $this->assertEquals(2, 2);
    }
    
    public function testModelLoaded() {
        $gametime = null;
        $this->assertNull($gametime);
        $gametime = new Model_GameTime();
        $this->assertNotNull($gametime);
    }
    
    public function testGetRawTimeType() {
        $gametime = new Model_GameTime();
        $rawTime = $gametime->getRawTime();
        $this->assertGreaterThan(0, $rawTime);
    }

    /**
     * @expectedException BadDaemonException
     */
    public function testGetRawTimeExceptionBadDaemon() {
        
        $gametime = new Model_GameTime();
        //bad daemon
        $rawTime = $gametime->getRawTime('/home/magnax/www/simtrd/d_bad.py');

    }
    
    /**
     * @expectedException DaemonNotRunningException
     */
    public function testGetRawTimeExceptionDaemonNotRunning() {
        
        //not running daemon
        $gametime = new Model_GameTime();
        $rawTime = $gametime->getRawTime('/home/magnax/www/simtrd/d_test.py');

    }
    
    public function testGetTime() {
        $gametime = new Model_GameTime();
        $t = $gametime->getTime();
        $this->assertInternalType('string', $t);
        $this->assertRegExp('/[0-9]{1,2}:[0-9]{2}:[0-9]{2}/', $t);
    }
    
    public function testGetDate() {
        $gametime = new Model_GameTime();
        $t = $gametime->getDate();
        $this->assertInternalType('string', $t);
        $this->assertRegExp('/[0-9]{1,4}-([0-1][0-9])|[0-9]/', $t);
    }
 
    public function testGetDateTime() {
        $gametime = new Model_GameTime();
        $t = $gametime->getDateTime();
        $this->assertInternalType('string', $t);
        $this->assertRegExp('/[0-9]{1,4}-([0-1][0-9])|[0-9] [0-9]{1,2}:[0-9]{2}:[0-9]{2}/', $t);
    }
    
    public function testDecodeRawTime() {
        $gametime = new Model_GameTime();
        $rawtime = 2116726; //
        $timeArray = $gametime->decodeRawTime($rawtime);
        $this->assertEquals(46, $timeArray['s']); //seconds
        $this->assertEquals(58, $timeArray['m']); //minutes
        $this->assertEquals(11, $timeArray['h']); //hours
        $this->assertEquals(24, $timeArray['d']); //total days
        $this->assertEquals(1, $timeArray['y']); //years
        $this->assertEquals(4, $timeArray['f']); //days of year

    }
    
    public function testFormatDateTime() {
        
        $gametime = new Model_GameTime();
        
        $year = 1728000; //seconds
        $day = 86400; //seconds
        $hour = 3600;
        $minute = 60;
        
        $d = array(0,0,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:0:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,0,0,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:0:59', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,0,1,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 0:1:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,2,59,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 2:59:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,1,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-1 0:0:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,0,23,59,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-0 23:59:59', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,19,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-19 0:0:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(0,19,23,59,59);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('0-19 23:59:59', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(1,0,0,0,0);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('1-0 0:0:0', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(1,4,12,45,3);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('1-4 12:45:3', $gametime->formatDateTime($rt, 'y-f h:m:s'));
        
        $d = array(112,14,5,7,54);
        $rt = ($d[0]*$year)+($d[1]*$day)+($d[2]*$hour)+($d[3]*$minute)+$d[4];
        $this->assertEquals('2254 5:7:54', $gametime->formatDateTime($rt, 'd h:m:s'));
        
    }
    
}

?>
