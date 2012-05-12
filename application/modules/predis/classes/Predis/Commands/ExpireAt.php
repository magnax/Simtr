<?php

class Predis_Commands_ExpireAt extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'EXPIREAT'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
