<?php

class Predis_Commands_SetAdd extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SADD'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
