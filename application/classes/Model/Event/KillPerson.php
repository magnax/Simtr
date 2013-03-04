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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $returned['skill'] = Model_Character::getSkillString($this->skill);
        
        $returned['wpid'] = ORM::factory('ItemType', $this->wpid)
            ->name;
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
