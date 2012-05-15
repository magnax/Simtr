<?php

class Predis_Commands_ZSetAdd extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZADD'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
