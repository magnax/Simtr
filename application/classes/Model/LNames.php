<?php

abstract class Model_LNames {

    protected $source;

    public function  __construct($source) {
        $this->source = $source;
    }

    public static function getInstance($source) {

        //if ($source instanceof Redisent) {
        if ($source instanceof Redis) {
            return new Model_LNames_Redis($source);
        }

    }

    abstract function getName($id_character, $id_location);
    abstract function setName($id_character, $id_location, $new_name);

}

?>
