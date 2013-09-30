<?php defined('SYSPATH') or die('No direct script access.');

class Model_Road extends ORM {   
    
    protected $_belongs_to = array(
        'location_1' => array(
            'model' => 'Location',
            'foreign_key' => 'location_1_id',
            'far_key' => 'id',
        ),
        'location_2' => array(
            'model' => 'Location',
            'foreign_key' => 'location_2_id',
            'far_key' => 'id',
        ),
    );

    public $levels = array(
        '0' => 'ścieżka',
        '1' => 'piaszczysta',
        '2' => 'brukowana',
        '3' => 'szosa',
        '4' => 'autostrada',
    );

    public function get_level_name() {
        return $this->levels[$this->level];
    }

    public function get_distance() {
        return sqrt(pow(abs($this->location_2->town->x - $this->location_1->town->x), 2) + pow(abs($this->location_2->town->y - $this->location_1->town->y), 2));
    }
    
    public function getDirection() {
        return $this->direction;
    }
    
    public function get_direction($reverse = false) {
        
        $direction = Utils::calculateDirection(
            $this->location_1->town->x, $this->location_1->town->y, 
            $this->location_2->town->x, $this->location_2->town->y
        );
        
        if ($reverse) {
            return Utils::reverseDirection($direction);
        }
        
        return $direction;
        
    }

    public function get_direction_string($reverse = false) {
        return Utils::getDirectionString($this->get_direction($reverse));
    }

    public function getDestinationLocationID() {
        return $this->end_location_id;
    }
    
    public function get_end($start_id) {
        return ($this->location_1_id == $start_id) ? $this->location_2_id : $this->location_1_id;
    }
    
    /**
     * checks if road can be upgraded
     * returns true if:
     * - road level is below 4 AND
     * - no project upgrading this road is present on both locations
     * @return boolean
     */
    public function can_be_upgraded() {
        
        if ($this->level == 4) {
            return false;
        } else {
            
            foreach (array($this->location_1, $this->location_2) as $location_id) {
                $location = new Model_Location($location_id);
                $projects = $location->getProjectsIds();
                foreach($projects as $project_id) {
                    $project = new Model_Project($project_id);
                    if ($project->type_id == 'Road' && $project->road_id == $this->id) {
                        return false;
                    }
                }
            }
            
        }
        
        return true;
        
    }
    
}

?>
