<?php defined('SYSPATH') or die('No direct script access.');

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
    public function action_e() {
        
        $pointed_road = new Model_Road($this->request->param('id'));
        $pointed_location = new Model_Location($pointed_road->get_end($this->location->id));
        
        //wysłanie eventu
        $event = new Model_Event();
        $event->type = Model_Event::POINT_EXIT;
        $event->date = $this->game->getRawTime();

        $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
        $event->add('params', array('name' => 'road_id', 'value' => $pointed_road->id));
        $event->add('params', array('name' => 'exit_id', 'value' => $pointed_location->id));

        $event->save();

        $event->notify($this->location->getHearableCharacters());
        
        //redirect to events page
        $this->redirect('events');
        
    }
    
    public function action_person() {
        
        $person = $this->request->param('id');
        
        /**
         * @todo check if this person can possibly be pointed
         */
        
        //wysłanie eventu
        $event = new Model_Event();
        $event->type = Model_Event::POINT_PERSON;
        $event->date = $this->game->getRawTime();

        $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
        $event->add('params', array('name' => 'rcpt', 'value' => $person));

        $event->save();

        $event->notify($this->location->getHearableCharacters());
        
        //redirect to events page
        $this->redirect('events');
        
    }
    
}

?>
