<?php defined('SYSPATH') or die('No direct access allowed!');
 
include_once APPPATH.'classes/model/Event.php';

class Model_EventTest extends Kohana_UnitTest_TestCase
{
    public function testAdd()
    {
        $this->assertEquals(2, 2);
    }
    
    public function testEventLoaded() {
        $event = new Model_Event(2);
        $this->assertTrue($event->loaded());
    }
    
}