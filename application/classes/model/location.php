<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends ORM {  
    
    protected $_has_one = array(
        'town' => array(
            'model' => 'town',
            'foreign_key' => 'location_id',
            'far_key' => 'id',
        ),
        'locationtype' => array(
            'model' => 'locationtype',
            'foreign_key' => 'id',
            'far_key' => 'locationtype_id',
        ),
    );
    
    protected $_has_many = array(
        'characters' => array(
            'model' => 'character',
            'foreign_key' => 'location_id',
            'far_key' => 'id'
        ),
        'resources' => array(
            'model' => 'resource',
            'through' => 'locations_resources',
            'foreign_key' => 'location_id',
            'far_key' => 'resource_id'
        ),
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
    
    public static function getRandomSpawnLocation() {
        $random_location = ORM::factory('location')
            ->where('locationtype_id', '=', 1)
            ->order_by(DB::expr('RAND()'))
            ->find();
        
        return $random_location;
    }
    
    public function addProject($project_id, $source) {
        
        $source->sadd("locations:{$this->id}:projects", $project_id);
        
    }
    
}

?>
