<?php defined('SYSPATH') or die('No direct access allowed!');

class Model_CharacterTest extends PHPUnit_Framework_TestCase {
    
    public function setUp() {
        parent::setUp();
    }

    public function testCharacterModelIsWorking() {
        $character = new Model_Character();
        $this->assertTrue($character instanceof Model_Character);
    }
    
}