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

    public function  __construct($source, $dict) {
        $this->source = $source;
        $this->dict = $dict;
    }

    public static function getInstance($source, $dict) {

        if ($source instanceof Predis_Client) {
            return new Model_ChNames_Redis($source, $dict);
        }

    }

    abstract function getName($id_character, $id_character1);
    abstract function setName($id_character, $id_character1, $new_name);

}

?>