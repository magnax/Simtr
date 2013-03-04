<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Notes extends Controller_Base_Character {
    
    public function action_index() {
        
        
        
    }
    
    /**
     * create new note
     */
    public function action_new() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            if ($this->request->post('id')) {
                $note = new Model_Note($this->request->post('id'));
                $msg = 'Zmieniono notatkę';
            } else {
                $note = new Model_Note();
                $msg = 'Utworzono notatkę';
            }
            
            $note->values($this->request->post());
            $note->editable = $this->request->post('editable') ? 1 : 0;
            $note->created_by = $this->character->id;
            $note->created_at = $this->game->raw_time;
            
            $note->save();
            
            $this->redis->sadd("notes:{$this->character->id}", $note->id);
            
            $this->redirectMessage($msg, '/events');
            
        } else {
            
            if ($this->request->param('id')) {
                $note = new Model_Note($this->request->param('id'));
                if ($note->id) {
                    $this->view->note = $note;
                } else {
                    $this->redirectError('Nieprawidłowa notatka', '/events');
                }
            } else {
                $this->view->note = new Model_Note();
            }
            
        }
        
    }
    
    public function action_view() {
        
        $note = new Model_Note($this->request->param('id'));
        if ($note->id) {
            $this->view->note = $note;
        } else {
            $this->redirectError('Nieprawidłowa notatka', '/events');
        }
        
    }
    
    public function action_put() {
        
        $note = new Model_Note($this->request->param('id'));
        if ($note->id) {
            $this->redis->srem("notes:{$this->character->id}", $note->id);
            $this->redis->sadd("locations:{$this->location->id}:notes", $note->id);
            
            //generate event
            $event = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::PUT_NOTE, $this->game->raw_time, $this->redis
                )
            );

            $event->setSender($this->character->getId());
            $event->setNote($note->title);

            $event->addRecipients($this->location->getVisibleCharacters());
            $event->send();

            $this->redirect('/events');
        } else {
            $this->redirectError('Nieprawidłowa notatka', '/events');
        }
        
    }
    
    public function action_get() {
        
        $note = new Model_Note($this->request->param('id'));
        
        if ($note->id) {
            $this->redis->srem("locations:{$this->location->id}:notes", $note->id);
            $this->redis->sadd("notes:{$this->character->id}", $note->id);
            
            //generate event
            $event = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::GET_NOTE, $this->game->raw_time, $this->redis
                )
            );

            $event->setSender($this->character->getId());
            $event->setNote($note->title);

            $event->addRecipients($this->location->getVisibleCharacters());
            $event->send();
            
            $this->redirect('/events');
        } else {
            $this->redirectError('Nieprawidłowa notatka', '/events');
        }
        
    }
    
    public function action_copy() {
        
        $note = new Model_Note($this->request->param('id'));
        if ($note->id) {
            $note_array = $note->as_array();
            
            $new_note = new Model_Note();
            array_shift($note_array); // To remove ID 
            //print_r($note_array);
            $new_note->values($note_array);
            $new_note->created_by = $this->character->id;
            $new_note->created_at = $this->game->raw_time;
            $new_note->save();
            $this->redis->sadd("notes:{$this->character->id}", $new_note->id);
            $this->redirect('/user/inventory');
        }
        
    }
    
    public function action_delete() {
        
        $note = new Model_Note($this->request->param('id'));
        if ($note->id && $note->created_by == $this->character->id) {
                        
            $this->redis->srem("notes:{$this->character->id}", $note->id);
            $note->delete();
            
            $this->redirect('/user/inventory');
        }
        
    }
    
}

?>
