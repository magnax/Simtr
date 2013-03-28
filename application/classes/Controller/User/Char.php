<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler postaci
 * - tworzenie nowej postaci
 */
class Controller_User_Char extends Controller_Base_Character {
    
    public function action_talkto() {
     
        if (HTTP_Request::POST == $this->request->method() && $this->request->post('text')) {
            
            $recipient = new Model_Character($this->request->post('character_id'));
            
            if (!$recipient->loaded()) {
                $this->redirectError('Tej osoby już tutaj nie ma', 'events');
            }
            
            //wysłanie eventu
            $event = new Model_Event();
            $event->type = Model_Event::TALK_TO;
            $event->date = $this->game->getRawTime();

            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'rcpt', 'value' => $recipient->id));
            $event->add('params', array('name' => 'text', 'value' => strip_tags($this->request->post('text'))));

            $event->save();

            $event->notify($this->location->getHearableCharacters());
                
        }

        $this->redirect('events');
    }
}

?>
