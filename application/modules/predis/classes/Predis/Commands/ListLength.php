<?php

class Predis_Commands_ListLength extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LLEN'; }
}

?>
