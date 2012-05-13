<?php

abstract class Model_LNames {

    protected $source;

    //dictionary used to return names in different languages
    protected $dict;
    
    //character id 
    protected $character_id = null;

    public function  __construct($source, $dict) {
        $this->source = $source;
        $this->dict = $dict;
    }

    public static function getInstance($source, $dict) {

        if ($source instanceof Predis_Client) {
            return new Model_LNames_Redis($source, $dict);
        }

    }

    public function setCharacter($character_id) {
        $this->character_id = $character_id;
    }

    abstract function getName($id_location);
    abstract function setName($id_location, $new_name);

}

?>
