<?php

abstract class Model_EventSender {

    protected $_event;

    public function  __construct(Model_Event $event) {
        $this->_event = $event;
    }

    public static function getInstance(Model_Event $event) {
        if ($event->getSource() instanceof Predis_Client) {
            return new Model_EventSender_Redis($event);
        }
    }

    public function addRecipients(array $recipients) {
        $this->_event->addRecipients($recipients);
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
    
    abstract public function send();

}

?>