<?php

class Predis_Commands_ListPopLastBlocking extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'BRPOP'; }
}

?>
