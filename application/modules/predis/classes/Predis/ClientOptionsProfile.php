<?php

class Predis_ClientOptionsProfile implements Predis_IClientOptionsHandler {
    public function validate($option, $value) {
        if ($value instanceof Predis_RedisServerProfile) {
            return $value;
        }
        if (is_string($value)) {
            return Predis_RedisServerProfile::get($value);
        }
        throw new InvalidArgumentException("Invalid value for option $option");
    }

    public function getDefault() {
        return Predis_RedisServerProfile::getDefault();
    }
}


?>
