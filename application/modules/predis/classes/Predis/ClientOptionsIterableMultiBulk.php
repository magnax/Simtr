<?php
class Predis_ClientOptionsIterableMultiBulk implements Predis_IClientOptionsHandler {
    public function validate($option, $value) {
        return (bool) $value;
    }

    public function getDefault() {
        return false;
    }
}
?>
