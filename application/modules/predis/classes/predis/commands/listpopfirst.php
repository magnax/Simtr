<?php

class Predis_Commands_ListPopFirst extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LPOP'; }
}

?>
