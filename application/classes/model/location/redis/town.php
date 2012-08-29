<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location_Redis_Town extends Model_Location_Town {
    
    public function getRaws() {
        return $this->source->smembers("locations:{$this->id}:raws");
    }
    
    
    public function getExits($character = null) {
         
        $exits = $this->source->smembers("roa:{$this->id}");
        
        $dict = Model_Dict::getInstance($this->source);
        
        $returned = array();
         
        foreach ($exits as $e) {
            
            $road = Model_LocationFactory::getInstance($this->source)->fetchOne($e);
            $destination_location_id = $road->getDestinationLocation($this->id);
            
            $destination_location = Model_LocationFactory::getInstance($this->source)
                ->fetchOne($destination_location_id);
            
            $name = $character ? Model_LNames::getInstance($this->source)
                ->getName($destination_location_id) : $destination_location->getName();
            
            $returned[] = array(
                'id' => $e,
                'level' => $dict ? $dict->getString($destination_location->getLevelString()) : $destination_location->getLevelString(), 
                'lid'=>$destination_location_id, 
                'name'=> $name,
                'distance'=>$destination_location->getDistance(),
                'direction'=>  Helper_Utils::getDirectionString($destination_location->getDirection($this->id))
            );
         }
         
         return count($returned)? $returned : null;
    }
    
    //get IDs of all buildings in location
    public function getBuildings() {
        return $this->source->smembers("twn:{$this->id}:bld");
    }
    
    public function calculateUsedSlots() {
        
    }
    
    public function getAllHearableCharacters($as_array = false) {}
    public function getAllVisibleCharacters($as_array = false) {}
    public function save() {}
}

?>
