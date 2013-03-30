<?php defined('SYSPATH') or die('No direct script access.');

/**
 * location names controller
 * allows get and remember location name
 */

class Controller_Lname extends Controller_Base_Character {
    
    public function action_index() {
        
        $location = new Model_Location($this->request->param('id'));
        
        if (!$location->loaded()) {
            $this->redirectError('Nieprawidłowa lokacja', 'events');
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            if ($location->id != $this->request->post('location_id')) {
                $this->redirectError('Nieprawidłowa lokacja', 'events');
            }
            
            $lname = ORM::factory('LName')
                ->where('char_id', '=', $this->character->id)
                ->and_where('location_id', '=', $this->request->post('location_id'))
                ->find();
            
            if ($this->request->post('name')) {
                if (!$lname->loaded()) {
                    $lname->char_id = $this->character->id;
                    $lname->location_id = $this->request->post('location_id');
                }
                $lname->name = trim(strip_tags($this->request->post('name')));
                $lname->save();
            } elseif ($lname->id) {
                //"forget" this character's name
                $lname->delete();
            }
            
            $this->redirect('events');
            
        }
            
        $this->view->location_id = $location->id;
        $this->view->name = $location->get_lname($this->character);
        
    }
    
}

?>
