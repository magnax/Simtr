<?php defined('SYSPATH') or die('No direct script access.');

class Model_Position extends OHM {
    
    /**
     * base speed for 1 second (in pixels)
     * 
     * base speed for bare foot
     * base speed for vehicles like tandem, rikshaw ===> BASE_SPEED * 3.0
     * base speed for vehicles like van, stationwagon, pickup ===> BASE_SPEED * 15.0
     */
    const BASE_SPEED = 0.001944;  // => 7 pixels / 3600 seconds;

    protected $_columns = array('dir', 'speed', 'dest', 'x', 'y', 'x1', 'y1', 'x2', 'y2', 'time');
    
    public function get_distance() {
        
        return Utils::calculateDistance($this->x1, $this->y1, $this->x2, $this->y2);
        
    }

    public function get_progress() {
        
        $dist = Utils::calculateDistance($this->x1, $this->y1, $this->x2, $this->y2);
        $progress = Utils::calculateDistance($this->x, $this->y, $this->x1, $this->y1);
        
        return $progress / $dist;
    }
    
    public function move($time) {
        
        $t = ($time - $this->time) * $this->speed;
        $angle = deg2rad($this->dir);
        
        $this->x = $this->x + ($t * sin($angle));
        $this->y = $this->y + ($t * cos($angle));       
        $this->time = $time;
        
        return $this;
        
    }
    
    public function back() {
        
        $this->dir = Utils::reverseDirection($this->dir);
        $this->dest = 2 / $this->dest;
        
        $x = $this->x1 + $this->x2;
        $this->x1 = $x - $this->x1;
        $this->x2 = $x - $this->x2;
        
        $x = $this->y1 + $this->y2;
        $this->y1 = $x - $this->y1;
        $this->y2 = $x - $this->y2;
        
        $this->save();
        
    }
    
    public function get_from_location_id() {
        
        //$column_name = "location_{$this->dest}_id";
        return $this->id;
        
    }
    
}

?>
