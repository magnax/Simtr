<?php defined('SYSPATH') or die('No direct script access.');

class Model_Skill extends ORM {
    
    static $level_names = array(
        0 => 'niezręcznie',
        1 => 'po amatorsku',
        2 => 'przeciętnie',
        3 => 'umiejętnie',
        4 => 'po mistrzowsku',
    );

    protected $_has_many = array(
        'characters' => array(
            'model' => 'Character',
            'through' => 'characters_skills',
        ),
    );
    
}

?>
