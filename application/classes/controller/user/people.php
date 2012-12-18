<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $characters_ids = $this->location->getHearableCharacters($this->character);
       
        $this->view->characters = array();
        
        $characters = ORM::factory('character')
            ->where('id', 'IN', DB::expr('('. join(',',$characters_ids).')'))
            ->find_all();
        
        foreach ($characters as $ch) {
;
            $this->view->characters[] = array(
                'name' => $this->character->getChname($ch->id),
                'id' => $ch->id,
                'gender' => $ch->sex,
            );
            
        }

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
            $weapon_attack = ORM::factory('itemtype', $_POST['weapon'])
                ->attack;
            
            //damage calculate
            //@todo: get char. strength and fighting skill into calculation
            $damage = floor($weapon_attack * ($_POST['strength']/100));
            
            //set victim damage (or generate death event if vitality <= 0
            $victim->setDamage($damage);
            
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
                
                //generate event & message
                $event_sender = Model_EventSender::getInstance(
                    Model_Event::getInstance(
                        Model_Event::KILL_PERSON, $this->game->raw_time, $this->redis
                    )
                );

            } else {
            
                //generate event & message
                $event_sender = Model_EventSender::getInstance(
                    Model_Event::getInstance(
                        Model_Event::HIT_PERSON, $this->game->raw_time, $this->redis
                    )
                );

                $event_sender->setDamage($damage);
                $event_sender->setShieldTypeID(-1);
                $event_sender->setShield(0);
                
                //set time of last attack
                $key = "hit:{$this->character->id}:{$character_id}";
                $this->redis->set($key, $this->game->raw_time);
                $this->redis->expire($key, Model_User::HIT_GAP);
            }
            
            $event_sender->setRecipient($character_id);           
            $event_sender->setSender($this->character->id);
                
            //set fighting skill
            $event_sender->setSkill($this->character->fighting);
            $event_sender->setWeaponTypeID($_POST['weapon']);
                
            $event_sender->addRecipients($this->location->getHearableCharacters());    
            $event_sender->send();
            
            Model_EventNotifier::notify(
                $event_sender->getEvent()->getRecipients(), 
                $event_sender->getEvent()->getId(), 
                $this->redis, $this->lang
            );
        
            $this->request->redirect('events');
        }
        
        //$this->view->weapons = array();
            
        $this->view->weapons = $this->character->getWeaponsList();
        //$this->view->weapons = $weapons_list;
        
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
