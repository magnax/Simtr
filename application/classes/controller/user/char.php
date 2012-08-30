<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler postaci
 * - tworzenie nowej postaci
 */
class Controller_User_Char extends Controller_Base_Character {
    
    public function action_talkto() {
     
        if (isset($_POST['text']) && $_POST['text']) {
            
            $recipient = new Model_Character($_POST['character_id']);
            
            $event_sender = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::TALK_TO, $this->game->raw_time, $this->redis
                )
            );
            
            $recipients = $this->location->getHearableCharacters();
            
            $event_sender->setText($_POST['text']);           
            //recipients to lista obiektów klasy Character
            $event_sender->setRecipient($recipient->id);
            $event_sender->setSender($this->character->id);
            $event_sender->addRecipients($recipients);
            
            $event_sender->send();
            
            $event_id = $event_sender->getEvent()->getId();
    
            Model_EventNotifier::notify($recipients, $event_id, $this->redis, $this->lang);
            
        }

        $this->request->redirect('events');
    }
}

?>
