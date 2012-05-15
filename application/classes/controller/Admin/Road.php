<?php

class Controller_Admin_Road extends Controller_Base_Admin {
    
    public function action_add($location_from_id) {
        
        $location = Model_Location::getInstance($this->redis)->findOneByID($location_from_id, null);
        
        $this->view->location = $location;
        
        if (isset($_POST['destination'])) {
            $dest_location = Model_Location::getInstance($this->redis)->findOneByID($_POST['destination_id'], null);
            $this->view->dest_location = $dest_location;
            $this->view->distance = Helper_Utils::calculateDistance(
                $location->getX(), $location->getY(),
                $dest_location->getX(), $dest_location->getY()
            );
            $this->view->direction = Helper_Utils::calculateDirection(
                $location->getX(), $location->getY(),
                $dest_location->getX(), $dest_location->getY()
            );
            $this->view->direction_string = Helper_Utils::getDirectionString($this->view->direction);
            $this->view->rev_direction = Helper_Utils::reverseDirection($this->view->direction);
            $this->view->rev_direction_string = Helper_Utils::getDirectionString($this->view->rev_direction);
            $this->view->levels = Model_Road::getInstance($this->redis)->getLevels();
        } elseif (isset($_POST['save'])) {
            
            $dest_location = Model_Location::getInstance($this->redis)->findOneByID($_POST['destination_id'], null);
            $road = Model_Road::getInstance($this->redis);
            
            $distance = Helper_Utils::calculateDistance(
                $location->getX(), $location->getY(),
                $dest_location->getX(), $dest_location->getY()
            );
            
            $road->setLocations($location->getID(), $dest_location->getID());
            $road->setDistance($distance);
            
            $direction = Helper_Utils::calculateDirection(
                $location->getX(), $location->getY(),
                $dest_location->getX(), $dest_location->getY()
            );
            
            $road->setDirection($direction);
            $road->setLevel($_POST['level']);
            
            $road->save();
            $location->addExit($road->getID());
            
            //second road, in reverse direction
            unset($road);
            $road = Model_Road::getInstance($this->redis);
            
            $road->setLocations($dest_location->getID(), $location->getID());
            $road->setDistance($distance);
            $road->setDirection(Helper_Utils::reverseDirection($direction));
            $road->setLevel($_POST['level']);
            
            $road->save();
            $dest_location->addExit($road->getID());
            
            echo 'Droga zapisana';
        } else {
        
            $this->view->destinations = Model_Location::getInstance($this->redis)->getAllLocations($location_from_id);
        }
        
    }
    
}


?>
