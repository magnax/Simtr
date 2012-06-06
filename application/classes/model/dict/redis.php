<?php

class Model_Dict_Redis extends Model_Dict {

    public function getString($str) {
        $trans = $this->source->get("dict:{$this->lang}:$str");

        return $trans ? $trans : $str;
    }

}

?>