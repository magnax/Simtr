<?php defined('SYSPATH') or die('No direct script access.');

class Controller_ChName extends Controller_Base_Character {
    
    public function action_index() {
        
        if ($_POST) {
            
            $chname = ORM::factory('chname')
                ->where('char_id', '=', $this->character->id)
                ->and_where('lookup_id', '=', $_POST['character_id'])
                ->find();
            
            if (!$chname) {
                $chname = new Model_ChName;
            }
            
            if ($_POST['name']) {
                if (!$chname->id) {
                    $chname->char_id = $this->character->id;
                    $chname->lookup_id = $_POST['character_id'];
                }
                $chname->name = trim($_POST['name']);
                $chname->save();
            } elseif ($chname->id) {
                $chname->delete();
            }
            
            Request::current()->redirect('events');
            
        }
        
        $character = new Model_Character($_GET['id']);
        
        if ($character->id) {
            
            $this->view->character_id = $_GET['id'];
            $name = ORM::factory('chname')->name($this->character->id, $_GET['id'])->name;
            if (!$name) {
                $name = $this->character->getUnknownName($_GET['id'], $this->lang);
            }
            $this->view->name = $name;
            
        } else {
            $this->session->set('err', 'Bad character');
            Request::current()->redirect('events');
        }
        
    }
    
}

?>