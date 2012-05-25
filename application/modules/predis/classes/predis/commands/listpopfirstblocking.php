<?php

class Predis_Commands_ListPopFirstBlocking extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'BLPOP'; }
}

?>
