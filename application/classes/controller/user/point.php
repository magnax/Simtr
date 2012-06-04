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
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::POINT_EXIT, $this->game->raw_time, $this->redis
            )
        );

        $event->setExit($exit_id);           
        //recipients to lista obiektów klasy Character
        //$event->setRecipient($recipient->getId());
        $event->setSender($this->character->getId());
        $event->addRecipients($this->location->getAllHearableCharacters($this->character->chnames));
        $event->send();
        
        //redirect to events page
        $this->request->redirect('user/event');
        
    }
    
}

?>
