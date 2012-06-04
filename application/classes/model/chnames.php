<?php

abstract class Model_ChNames {

    protected $age_strings = array(
        'K'=>array(
            20=>'dwudziestoletnia',
            30=>'trzydziestoletnia',
            40=>'czterdziestoletnia',
            50=>'pięćdziesięcioletnia',
            60=>'sześćdziesięcioletnia',
            70=>'siedemdziesięcioletnia',
            80=>'osiemdziesięcioletnia',
            'old'=>'bardzo stara',
        ),
        'M'=>array(
            20=>'dwudziestoletni',
            30=>'trzydziestoletni',
            40=>'czterdziestoletni',
            50=>'pięćdziesięcioletni',
            60=>'sześćdziesięcioletni',
            70=>'siedemdziesięcioletni',
            80=>'osiemdziesięcioletni',
            'old'=>'bardzo stary',
        )
    );

    protected $source;

    protected $dict;
    
    //current time (for counting age of other characters
    protected $raw_time;

    public function  __construct($source, $dict, $raw_time) {
        $this->source = $source;
        $this->dict = $dict;
        $this->raw_time = $raw_time;
    }

    public static function getInstance($source, $dict, $raw_time) {

        //if ($source instanceof Redisent) {
        if ($source instanceof Predis_Client) {
            return new Model_ChNames_Redis($source, $dict, $raw_time);
        }

    }
    
    public function getRawTime() {
        return $this->raw_time;
    }

    abstract function getName($id_character, $id_character1);
    abstract function setName($id_character, $id_character1, $new_name);

}

?>