<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_People extends Controller_Base_Character {

    public function action_index() {

        $characters = $this->location->getHearableCharacters($this->character);
       
        $this->view->characters = array();
        
        foreach ($characters as $ch) {
            $name = ORM::factory('chname')->name($this->character->id, $ch)->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($ch, $this->lang);
            }
            $this->view->characters[] = array(
                'name' => $name,
                'id' => $ch,
            );
        }

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
            
            //get attack strength of weapon
            $weapon_attack = Model_ItemType::getInstance($this->redis)
                ->fetchOne($_POST['weapon'])
                ->getAttack();
            
            //damage calculate
            //@todo: get char. strength and fighting skill into calculation
            $damage = floor($weapon_attack * ($_POST['strength']/100));
            
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
            $event->setWeaponTypeID($_POST['weapon']);
            
            $event->setDamage($damage);
            $event->setShieldTypeID(0);
            $event->setShield(0);
            
            $event->addRecipients($this->location->getAllHearableCharacters($this->character->chnames));
            $event->send();
        
            $this->request->redirect('/user/event');
        }
        
        $this->view->weapons = array();
            
        $weapons_list = $this->character->getWeaponsList();
        foreach ($weapons_list as $w) {
            $this->view->weapons[$w] = Model_Dict::getInstance($this->redis)
                ->getString(Model_ItemType::getInstance($this->redis)
                    ->getName($w));
        }
        
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
