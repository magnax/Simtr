<?php defined('SYSPATH') or die('No direct script access ST.');
 
class SampleTest extends PHPUnit_Framework_TestCase {
   
    public function test_add()
    {
        $this->assertEquals(2, 2+1);
    }
    
}
