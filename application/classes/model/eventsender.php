<?php

abstract class Model_EventSender {

    protected $_event;

    public function  __construct(Model_Event $event) {
        $this->_event = $event;
    }

    public static function getInstance(Model_Event $event) {
        //if ($event->getSource() instanceof Redisent) {
        if ($event->getSource() instanceof Redisent) {
            return new Model_EventSender_Redis($event);
        }
    }

    public function addRecipients(array $recipients) {
        $this->_event->addRecipients($recipients);
    }

    public function getEvent() {
        return $this->_event;
    }

    public function setResource($res_id, $amount) {
        $this->_event->setResource($res_id, $amount);
    }

    public function setSender($ch) {
        $this->_event->setSender($ch);
    }
    
    public function setRecipient($ch) {
        $this->_event->setRecipient($ch);
    }

    public function setText($t) {
        $this->_event->setText($t);
    }

    public function setExit($exit_id) {
        $this->_event->setExit($exit_id);
    }
    
    public function setSkill($skill) {
        $this->_event->setSkill($skill);
    }

    public function setWeaponTypeID($id) {
        $this->_event->setWeaponTypeID($id);
    }
    
    public function setDamage($amount) {
        $this->_event->setDamage($amount);
    }
    
    public function setShieldTypeID($id) {
        $this->_event->setShieldTypeID($id);
    }
    
    public function setShield($id) {
        $this->_event->setShield($id);
    }
    
    public function setItem($item_id) {
        $this->_event->setItem($item_id);
    }
    
    public function setLocationType($type) {
        $this->_event->setLocationType($type);
    }
    
    public function setLocationId($location_id) {
        $this->_event->setLocationId($location_id);
    }
    
    public function setExitLocationId($location_id) {
        $this->_event->setExitLocationId($location_id);
    }
    
    abstract public function send();

}

?>
