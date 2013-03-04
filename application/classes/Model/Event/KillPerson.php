<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_KillPerson extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia HIT_PERSON
     */
    protected $recipient;
    protected $skill;
    protected $weapon_type_id;

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function setSkill($skill) {
        $this->skill = $skill;
    }
    
    public function setWeaponTypeID($id) {
        $this->weapon_type_id = $id;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;
        $arr['skill'] = $this->skill;
        $arr['wpid'] = $this->weapon_type_id;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        $returned['skill'] = Model_Character::getSkillString($event_data['skill']);
        
        if (in_array('rcpt', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['rcpt'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['rcpt'], $lang);
            }
            $returned['rcpt'] = '<a href="chname?id='.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        $returned['wpid'] = ORM::factory('itemtype', $event_data['wpid'])
                ->name;
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
