<?php defined('SYSPATH') or die('No direct access allowed!');

class Model_CharacterTest extends Kohana_UnitTest_TestCase {
    
    public function setUp() {
        parent::setUp();
    }

    public function testCharacterCanDropResource() {
        $character = new Model_Character(2);
        $this->assertTrue($character->loaded());
    }
    
}