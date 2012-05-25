<?php

class Predis_Commands_SetMultiplePreserve extends Predis_Commands_SetMultiple {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'MSETNX'; }
    public function parseResponse($data) { return (bool) $data; }
}

?>
