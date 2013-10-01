<?php defined('SYSPATH') or die('No direct script access.');

class Model_CharacterSkill extends ORM {
    
    public $_table_name = 'characters_skills';
    
    //protected $_table_columns = array('column' => 'level');
    
    protected $_belongs_to = array(
        'skill' => array(),
        'character' => array(),
//        'skill' => array(
//            'model' => 'Skill',
//            'foreign_key' => 'skill_id',
//            'far_key' => 'id',
//        ),
    );
    
}

?>
