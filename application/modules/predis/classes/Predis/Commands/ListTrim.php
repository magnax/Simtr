<?php

class Predis_Commands_ListTrim extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'LTRIM'; }
}

?>
