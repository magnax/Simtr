<?php

class Predis_Commands_HashDelete extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HDEL'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
