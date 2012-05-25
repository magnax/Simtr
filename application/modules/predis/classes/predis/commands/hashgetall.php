<?php

class Predis_Commands_HashGetAll extends Predis_MultiBulkCommand {
    public function getCommandId() { return 'HGETALL'; }
    public function parseResponse($data) {
        if ($data instanceof Iterator) {
            return new Predis_Shared_MultiBulkResponseKVIterator($data);
        }
        $result = array();
        for ($i = 0; $i < count($data); $i++) {
            $result[$data[$i]] = $data[++$i];
        }
        return $result;
    }
}

?>
