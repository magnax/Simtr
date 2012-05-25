<?php

class Predis_Commands_ZSetRemove extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZREM'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
