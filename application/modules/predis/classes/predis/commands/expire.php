<?php

class Predis_Commands_Expire extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'EXPIRE'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
