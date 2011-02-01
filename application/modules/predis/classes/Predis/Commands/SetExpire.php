<?php

class Predis_Commands_SetExpire extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SETEX'; }
}

?>
