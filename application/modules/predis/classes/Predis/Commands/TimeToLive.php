<?php

class Predis_Commands_TimeToLive extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'TTL'; }
}

?>
