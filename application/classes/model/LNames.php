<?php

abstract class Model_LNames {

    protected $source;

    protected $dict;

    public function  __construct($source, $dict) {
        $this->source = $source;
        $this->dict = $dict;
    }

    public static function getInstance($source, $dict) {

        if ($source instanceof Predis_Client) {
            return new Model_LNames_Redis($source, $dict);
        }

    }

    abstract function getName($id_character, $id_location);
    abstract function setName($id_character, $id_location, $new_name);

}

?>
