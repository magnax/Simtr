<?php

class Predis_Commands_Unwatch extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'UNWATCH'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
