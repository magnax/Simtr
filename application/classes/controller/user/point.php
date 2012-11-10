<?php

/**
 * 'pointing' controller, responsible for generating events triggered by pointing
 * some objects, people, roads, projects
 *  
 */
class Controller_User_Point extends Controller_Base_Character {
    
    /**
     * pointing exit (road)
     * @param int $exit_id 
     */
    public function action_e($exit_id) {
        
        //generate event & message
        $event_sender = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::POINT_EXIT, $this->game->raw_time, $this->redis
            )
        );

        $event_sender->setExit($exit_id);           

        $event_sender->setSender($this->character->id);
        
        $event_sender->addRecipients($this->location->getHearableCharacters());
        $event_sender->send();
        
        //redirect to events page
        $this->request->redirect('events');
        
    }
    
    public function action_person() {
        
        $person = $this->request->param('id');
        
        /**
         * @todo check if this person can possibly be pointed
         */
        
        //generate event & message
        $event_sender = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::POINT_PERSON, $this->game->raw_time, $this->redis
            )
        );

        $event_sender->setRecipient($person);           
        $event_sender->setSender($this->character->id);

        $event_sender->addRecipients($this->location->getHearableCharacters());
        $event_sender->send();
            
        //redirect to events page
        $this->request->redirect('events');
    }
    
}

?>
