<?php

class Predis_Commands_SetPreserve extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
