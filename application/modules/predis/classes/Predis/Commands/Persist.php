<?php

class Predis_Commands_Persist extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'PERSIST'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
