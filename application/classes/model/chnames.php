<?php

abstract class Model_ChNames {

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }

    public static function getInstance($source) {

        //if ($source instanceof Redisent) {
        if ($source instanceof Redis) {
            return new Model_ChNames_Redis($source);
        }

    }

    abstract function getName($id_character, $id_character1);
    abstract function setName($id_character, $id_character1, $new_name);

}

?>