<?php

class Predis_Commands_HashSet extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HSET'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
