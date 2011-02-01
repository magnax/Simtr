<?php

class Predis_ClientOptionsThrowOnError implements Predis_IClientOptionsHandler {
    public function validate($option, $value) {
        return (bool) $value;
    }

    public function getDefault() {
        return true;
    }
}

?>
