<?php defined('SYSPATH') or die('No direct script access.');

/** 
 * ChName - character name controller
 * allows to get and change remembered characters names
 */

class Controller_Chname extends Controller_Base_Character {
    
    /**
     * change name of given character
     */
    public function action_index() {
        
        $character = new Model_Character($this->request->param('id'));
        $character_name = $this->character->getChname($this->request->param('id'));
        
        if (!$character->loaded()) {
            $this->redirectError('Nieprawidłowa postać', 'events');
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            if ($character->id != $this->request->post('character_id')) {
                $this->redirectError('Nieprawidłowa postać', 'events');
            }
            
            $chname = ORM::factory('ChName')
                ->where('char_id', '=', $this->character->id)
                ->where('lookup_id', '=', $this->request->post('character_id'))
                ->find();
            
            if ($this->request->post('name')) {
                if (!$chname->loaded()) {
                    $chname->char_id = $this->character->id;
                    $chname->lookup_id = $_POST['character_id'];
                }
                $chname->name = trim(strip_tags($this->request->post('name')));
                $chname->save();
            } elseif ($chname->id) {
                //"forget" this character's name
                $chname->delete();
            }
            
            $this->redirect('events');
            
        }
        
        $this->view->name = $character_name;
        $this->view->character = $character;
        
    }
    
}

?>
