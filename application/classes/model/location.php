<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends ORM {  
    
    protected $_has_one = array(
        'town' => array(
            'model' => 'town',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        )
    );
    
    protected $_has_many = array(
        'characters' => array(
            'model' => 'character',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        )
    );
    
    public function getHearableCharacters() {
        
        $returned = array();
        //for now only, these are actually all visible characters
        $all = $this->characters->find_all()->as_array();
        foreach ($all as $character) {
            $returned[] = $character->id;
        }
        
        return $returned;
        
    }
    
}

?>
