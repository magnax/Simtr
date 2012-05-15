<?php

class Predis_Commands_HashExists extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HEXISTS'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
