<?php

class Model_Event_Spawn extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia TALK_ALL
     */
    protected $newspawn_id;
    protected $sender;
    protected $location;

    public function  __construct($date, $source) {
        $this->type = self::SPAWN;
        $this->source = $source;
        $this->date = $date;
    }

    public function setNewspawnID($id) {
        $this->newspawn_id = $id;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function setLocation(Model_Location $location) {
        $this->location = $location;
    }

    public function send() {

    }

}

?>