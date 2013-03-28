<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $this->view->characters = $this->location->get_characters($this->character);
        
    }

    public function action_hit() {
        
        $character_id = $this->request->param('id');
        
        $hitted = $this->redis->get("hit:{$this->character->id}:{$character_id}");
        
        if ($hitted) {
            
            $time_elapsed = $this->game->raw_time - $hitted;
            
            //in case the key not expired at given time
            if ($time_elapsed > Model_User::HIT_GAP) {
                $this->redis->del("hit:{$this->character->id}:{$character_id}");
            } else {
                $time = gmdate('H:i:s', Model_User::HIT_GAP - ($this->game->raw_time - $hitted));
                $this->redirectError('Możesz uderzyć tę postać za '.$time . ', '. $time_elapsed, 'events');
            }
            
        }
        
        //initialize form validation
        $post = Validation::factory($_POST);
        
        //labels
        $post->label('weapon', 'Weapon');
        $post->label('strength', 'Siła');
        
        //rules for fields
        $post->rule('weapon', 'digit');
        $post->rule('strength', 'numeric');
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            //get victim character
            $victim = new Model_Character($character_id);
            
            //get attack strength of weapon
            $weapon_attack = ORM::factory('ItemType', $_POST['weapon'])
                ->attack;
            
            //damage calculate
            //@todo: get char. strength and fighting skill into calculation
            $damage = floor($weapon_attack * ($_POST['strength']/100));
            
            //set victim damage (or generate death event if vitality <= 0
            $victim->setDamage($damage);
            
            //creating event
            $event = new Model_Event();
            
            if ($victim->isDying()) {
                
                $body = new Model_Corpse();
                $body->character_id = $victim->id;
                $body->location_id = $victim->location_id;
                $body->created = $this->game->raw_time;
                $body->weight = 60000;
                
                //todo: get victim's clothes and set to body
                //todo: get all victim's items and put on the ground
                
                $body->save();
                
                $victim->location_id = null;
                $victim->life = 0;
                $victim->save();
                
                $event->type = Model_Event::KILL_PERSON;

            } else {
            
                $event->type = Model_Event::HIT_PERSON;
                
                $event->add('params', array('name' => 'dmg', 'value' => $damage));
                //shield ID (item from inventory)
                $event->add('params', array('name' => 'shid', 'value' => -1));
                //poinst saved from shield
                $event->add('params', array('name' => 'shd', 'value' => 0));
                
                //set time of last attack
                $key = "hit:{$this->character->id}:{$character_id}";
                $this->redis->set($key, $this->game->raw_time);
                $this->redis->expire($key, Model_User::HIT_GAP);
            }

            $event->date = $this->game->getRawTime();

            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'rcpt', 'value' => $character_id));
            $event->add('params', array('name' => 'skill', 'value' => $this->character->fighting));
            $event->add('params', array('name' => 'wpid', 'value' => $_POST['weapon']));

            $event->save();

            $event->notify($this->location->getHearableCharacters());
            
            $this->redirect('events');
        }
            
        $this->view->weapons = $this->character->getWeaponsList();
        
        $this->view->strengths = array(
            '0' => 'bez siły',
            '30' => 'słabo',
            '60' => 'średnio',
            '100' => 'z całej siły'
        );
        
        //character being hitted
        $this->view->character_id = $character_id;
        
    }
    
}

?>
