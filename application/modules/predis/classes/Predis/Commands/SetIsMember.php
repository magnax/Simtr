<?php

class Predis_Commands_SetIsMember extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SISMEMBER'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
