<?php

class Predis_Commands_SlaveOf extends Predis_MultiBulkCommand {
    public function canBeHashed()  { return false; }
    public function getCommandId() { return 'SLAVEOF'; }
    public function filterArguments(Array $arguments) {
        if (count($arguments) === 0 || $arguments[0] === 'NO ONE') {
            return array('NO', 'ONE');
        }
        return $arguments;
    }
}

?>
