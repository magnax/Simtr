<?php

class Predis_Commands_SetUnionStore extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'SUNIONSTORE'; }
}

?>
