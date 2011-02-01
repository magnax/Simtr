<?php

class Model_Dict_Redis extends Model_Dict {

    public function getString($str) {
        $trans = $this->source->get("dict:{$this->lang}:$str");
        if (!$trans) {
            $trans = $this->source->get("dict:{$this->default_lang}:$str");
            if (!$trans) {
                $trans = $str;

            }
        }
        return $trans;
    }

}

?>