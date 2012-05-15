<?php

class Predis_Commands_ZSetScore extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'ZSCORE'; }
}

?>
