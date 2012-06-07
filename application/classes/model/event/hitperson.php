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

    public function dispatchArgs($event_data, $args, $character) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = '<a href="'.URL::base(true).'user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        $returned['skill'] = Model_Dict::getInstance($this->source)
            ->getString('fight'.$event_data['skill']);
        
        if (in_array('rcpt', $args)) {
            $name = $character->getChname($event_data['rcpt']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['rcpt']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['rcpt'] = '<a href="'.URL::base(true).'user/char/nameform/'.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        $weapon_name = Model_ItemType::getInstance($this->source)
            ->getName($event_data['wpid']);
        
        $returned['wpid'] = Model_Dict::getInstance($this->source)
            ->getString($weapon_name);
        
        if (in_array('dmg', $args)) {
            $returned['dmg'] = $event_data['dmg'];
        }
        
        if (in_array('shid', $args)) {
            $shield_name = Model_ItemType::getInstance($this->source)
                ->getName($event_data['shid']);

            $returned['shid'] = Model_Dict::getInstance($this->source)
                ->getString($shield_name);
        }
        
        if (in_array('shd', $args)) {
            $returned['shd'] = $event_data['shd'];
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>
