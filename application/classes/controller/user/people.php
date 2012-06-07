<?php

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $characters = $this->location
            ->getAllHearableCharacters(true, $this->character);
        //return;
        foreach ($characters as &$ch) {
            $name = $this->character->getChname($ch['id']);
            if (!$name) {
                $name = $this->character->getUnknownName($ch['id']);
                $name = Model_Dict::getInstance($this->redis)->getString($name);
            }
            $ch['name'] = $name;
        }

        $this->view->characters = $characters;
    }

    public function action_hit($character_id) {
        
        //initialize form validation
        $post = Validate::factory($_POST);
        
        //filters for fields
        $post->filter(TRUE, 'trim');
        
        //labels
        $post->label('weapon', 'Weapon');
        $post->label('strength', 'Siła');
        
        //rules for fields
        $post->rule('weapon', 'digit');
        $post->rule('strength', 'numeric');
        
        if ($post->check()) {
            
            //get victim character
            $victim = Model_Character::getInstance($this->redis)
                ->fetchOne($character_id);
            
            //damage calculate
            $damage = floor(10 * ($_POST['strength']/100));
            
            //set victim damage (or generate death event if vitality <= 0
            $victim->setDamage($damage);
            
            if ($victim->isDying()) {
                //generate KILL_PERSON event
                //generate DIE_BY_PERSON event
            }
            
            //generate event & message
            $event = Model_EventSender::getInstance(
                Model_Event::getInstance(
                    Model_Event::HIT_PERSON, $this->game->raw_time, $this->redis
                )
            );

            $event->setRecipient($character_id);           
            $event->setSender($this->character->getId());
            
            //set fighting skill
            $event->setSkill($this->character->fighting);
            $event->setWeaponTypeID(1);
            
            $event->setDamage($damage);
            $event->setShieldTypeID(0);
            $event->setShield(0);
            
            $event->addRecipients($this->location->getAllHearableCharacters($this->character->chnames));
            $event->send();
        
            $this->request->redirect('/user/event');
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
