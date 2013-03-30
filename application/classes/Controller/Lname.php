<?php defined('SYSPATH') or die('No direct script access.');

/**
 * location names controller
 * allows get and remember location name
 */

class Controller_Lname extends Controller_Base_Character {
    
    public function action_index() {
        
        if ($_POST) {
            
            $lname = ORM::factory('LName')
                ->where('char_id', '=', $this->character->id)
                ->and_where('location_id', '=', $_POST['location_id'])
                ->find();
            
            if (!$lname) {
                $lname = new Model_LName;
            }
            
            if ($_POST['name']) {
                if (!$lname->id) {
                    $lname->char_id = $this->character->id;
                    $lname->location_id = $_POST['location_id'];
                }
                $lname->name = trim($_POST['name']);
                $lname->save();
            } elseif ($lname->id) {
                //"forget" this character's name
                $lname->delete();
            }
            
            Request::current()->redirect('events');
            
        }
        
        $location = new Model_Location($_GET['id']);
        
        if ($location->id) {
            
            $this->view->location_id = $_GET['id'];
            $name = ORM::factory('LName')->name($this->character->id, $_GET['id'])->name;
            if (!$name) {
                $name = 'unknown location';
            }
            $this->view->name = $name;
            
        } else {
            $this->session->set('err', 'Bad location');
            Request::current()->redirect('events');
        }
        
    }
    
}

?>
