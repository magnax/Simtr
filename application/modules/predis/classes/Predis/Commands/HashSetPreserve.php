<?php

class Predis_Commands_HashSetPreserve extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HSETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
