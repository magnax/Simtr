<?php

class Model_Event_HitPerson extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia HIT_PERSON
     */
    protected $recipient;
    protected $skill;
    protected $weapon_type_id;
    protected $damage;
    protected $shield_type_id;
    protected $shield;

    public function setRecipient($ch) {
        $this->recipient = $ch;
    }

    public function setSkill($skill) {
        $this->skill = $skill;
    }
    
    public function setWeaponTypeID($id) {
        $this->weapon_type_id = $id;
    }
    
    public function setDamage($amount) {
        $this->damage = $amount;
    }
    
    public function setShieldTypeID($id) {
        $this->shield_type_id = $id;
    }
    
    public function setShield($amount) {
        $this->shield = $amount;
    }
    
    public function toArray() {

        $arr = parent::toArray();

        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->recipient;
        $arr['skill'] = $this->skill;
        $arr['wpid'] = $this->weapon_type_id;
        $arr['dmg'] = $this->damage;
        $arr['shid'] = $this->shield_type_id;
        $arr['shd'] = $this->shield;

        return $arr;

    }

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $returned['skill'] = Model_Character::getSkillString($this->skill);
        
        $returned['wpid'] = ORM::factory('ItemType', $this->wpid)
                ->name;
        
        if (in_array('dmg', $args)) {
            $returned['dmg'] = $this->dmg;
        }
        
        if (in_array('shid', $args)) {
            $returned['shid'] = ORM::factory('ItemType', $this->shid)
                ->name;
        }
        
        if (in_array('shd', $args)) {
            $returned['shd'] = $this->shd;
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
