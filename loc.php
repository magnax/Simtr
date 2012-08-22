<?php

abstract class Location {
    protected $name;
    protected $class;
    function __construct($data = null) {
        if ($data) {
            $this->id = $data['id'];
            $this->name = $data['name'];
            $this->class = $data['class'];
        }
    }
    function getName() { return $this->name; }
    function getClass() { return $this->class; }
    function updateFromPost($post) {
        foreach ($post as $key => $value) {
            $this->$key = $value;
        }
    }
    function toArray() {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'class' => $this->class
        );
    }

}

abstract class Town extends Location {
    protected $used_place;
    protected $type;
    function __construct($data) {
        parent::__construct($data);
        $this->used_place = $data['used_place'];
        $this->type = $data['type'];
    }
    function toArray() {
        return array_merge(parent::toArray(), array(
            'used_place' => $this->used_place,
            'type' => $this->type
        ));
        
    }

    abstract function getRaws();
    
}

abstract class Building extends Location {
    protected $capacity;
    protected $type; //other than town->type
    function __construct($data) {
        parent::__construct($data);
        $this->capacity = $data['capacity'];
        $this->type = $data['type'];
    }
    function toArray() {
        return array_merge(parent::toArray(), array(
            'capacity' => $this->capacity,
            'type' => $this->type,
        ));
        
    }
    abstract function getRooms();
    
}

abstract class Vehicle extends Location {
    protected $capacity;
    protected $speed;
    protected $type; //other than town->type
    function __construct($data) {
        parent::__construct($data);
        $this->capacity = $data['capacity'];
        $this->speed = $data['speed'];
        $this->type = $data['type'];
    }
    function toArray() {
        return array_merge(parent::toArray(), array(
            'capacity' => $this->capacity,
            'speed' => $this->speed,
            'type' => $this->type,
        ));
        
    }
    
}

class LocationFactory {
    static function getInstance($source, $data = null) {
        if ($data) {
            switch ($data['class']) {
                case 'twn':
                    $class = 'Town_'.$source;
                    break;
                case 'bld':
                    $class = 'Building_'.$source;
                    break;
                case 'veh':
                    $class = 'Vehicle_'.$source;
            }
        } else {
            $class = 'Location_'.$source;
        }
        return new $class($data);
    }

}

class Location_Redis extends Location {
    function fetchOne($id) {
        if ($id == 23) {
            $data = array(
                'id' => 23,
                'class' => 'twn',
                'name' => 'Town_23',
                'type' => 'mountains',
                'used_place' => 4,
            );
        } elseif ($id == 24) {
            $data = array(
                'id' => 24,
                'class' => 'bld',
                'name' => 'Building_24',
                'type' => 'wooden hat',
                'capacity' => 399,
                
            );
        } elseif ($id == 25) { //vehicle
            $data = array(
                'id' => 25,
                'class' => 'veh',
                'name' => 'Vehicle_25',
                'type' => 'kombi',
                'capacity' => 399,
                'speed' => 45,
                
            );
        }
        return LocationFactory::getInstance('Redis', $data);
    }
    function save($o) {
        echo $o->getClass();
        echo ' saved as:<br>';
        echo json_encode($o->toArray());
        echo '<br>';
    }
}

class Town_Redis extends Town {

    function getRaws() {echo 'raws<br>'; }
    function save() {
        LocationFactory::getInstance('Redis')->save($this);
    }
}

class Building_Redis extends Building {

    function getRooms() {echo 'rooms<br>'; }
    function save() {
        LocationFactory::getInstance('Redis')->save($this);
    }
}

class Vehicle_Redis extends Vehicle {
    function save() {
        LocationFactory::getInstance('Redis')->save($this);
    }
}

$town_location = LocationFactory::getInstance('Redis')->fetchOne(23);
$town_location->getRaws();
$town_location->save();

$bld_location = LocationFactory::getInstance('Redis')->fetchOne(24);
$bld_location->getRooms();
$bld_location->save();
//
$veh_location = LocationFactory::getInstance('Redis')->fetchOne(25);
$veh_location->save();

$post = array(
    'class' => 'veh',
    'capacity' => 1000,
    'speed' => 60,
    'type' => 'van'
);
$generic_location = LocationFactory::getInstance('Redis', $post);
echo $generic_location->getClass().'<br>';
$generic_location->save();
$post = array(
    'class' => 'veh',
    'capacity' => 1500,
    'speed' => 40,
    'type' => 'van'
);

echo '<br><br>OK';
?>
