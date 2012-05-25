<?php

class Predis_Commands_SetRemove extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SREM'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
