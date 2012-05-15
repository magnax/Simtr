<?php

class Predis_Commands_Exists extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'EXISTS'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
