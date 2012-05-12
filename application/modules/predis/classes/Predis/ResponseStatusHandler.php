<?php

class Predis_ResponseStatusHandler implements Predis_IResponseHandler {
    public function handle(Predis_Connection $connection, $status) {
        if ($status === Predis_Protocol::OK) {
            return true;
        }
        else if ($status === Predis_Protocol::QUEUED) {
            return new Predis_ResponseQueued();
        }
        return $status;
    }
}

?>
