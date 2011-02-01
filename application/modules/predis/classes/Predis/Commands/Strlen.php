<?php

class Predis_Commands_Strlen extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'STRLEN'; }
}

?>
