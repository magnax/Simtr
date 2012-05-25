<?php

class Model_Event_PointExit extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia POINT_EXIT
     */

    /**
     * identyfikator drogi
     *
     * @var <type> int
     */
    protected $exit_id;

    protected $sender;

    public function setExit($exit_id) {
        $this->exit_id = $exit_id;
    }

    public function setSender($ch) {
        $this->sender = $ch;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['exit_id'] = $this->exit_id;
        $arr['sndr'] = $this->sender;

        return $arr;

    }

    public function send() {}

}

?>
