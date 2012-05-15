<?php

class Predis_Commands_ZSetIntersectionStore extends Predis_Commands_ZSetUnionStore {
    public function getCommandId() { return 'ZINTERSTORE'; }
}

?>
