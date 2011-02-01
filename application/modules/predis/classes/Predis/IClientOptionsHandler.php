<?php

interface Predis_IClientOptionsHandler {
    public function validate($option, $value);
    public function getDefault();
}

?>
