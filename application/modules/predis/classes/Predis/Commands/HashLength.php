<?php

class Predis_Commands_HashLength extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HLEN'; }
}

?>
