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
        
        if ($_POST) {
            
            $chname = ORM::factory('ChName')
                ->name($this->character->id, $_POST['character_id']);
            
            if ($_POST['name']) {
                if (!$chname->id) {
                    $chname->char_id = $this->character->id;
                    $chname->lookup_id = $_POST['character_id'];
                }
                $chname->name = trim($_POST['name']);
                $chname->save();
            } elseif ($chname->id) {
                //"forget" this character's name
                $chname->delete();
            }
            
            $this->redirect('events');
            
        }
        
        $character = new Model_Character($_GET['id']);
        
        if ($character->id) {
            
            $this->view->character_id = $_GET['id'];
            $this->view->name = $this->character->getChname($_GET['id']);
            $this->view->character = $character;
            
        } else {
            $this->session->set('err', 'Bad character');
            $this->redirect('events');
        }
        
    }
    
}

?>
