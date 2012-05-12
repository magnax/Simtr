<?php

class Predis_ResponseQueued {
    public $queued = true;

    public function __toString() {
        return Predis_Protocol::QUEUED;
    }
}

?>
