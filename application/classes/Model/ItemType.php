<?php defined('SYSPATH') or die('No direct script access.');

class Model_ItemType extends ORM {
    
    protected $_belongs_to = array(
        'projecttype' => array(
            'model' => 'ProjectType',
            'foreign_key' => 'projecttype_id',
            'far_key' => 'id',
        ),
    );
    
    protected $_has_many = array(
        'tools' => array(
            'model' => 'Tool',
            'foreign_key' => 'itemtype_id',
            'far_key' => 'id',
        ),
    );

    public static $states = array(
        'brand_new' => array(
            'M' => 'całkiem nowy',
            'K' => 'całkiem nowa',
            'N' => 'całkiem nowe'
        ),
        'new' => array(
            'M' => 'nowy',
            'K' => 'nowa',
            'N' => 'nowe'
        ),
        'used' => array(
            'M' => 'używany',
            'K' => 'używana',
            'N' => 'używane'
        ),
        'often_used' => array(
            'M' => 'często używany',
            'K' => 'często używana',
            'N' => 'często używane'
        ),
        'crumbling' => array(
            'M' => 'zużyty',
            'K' => 'zużyta',
            'N' => 'zużyte'
        )
    );

    public static function getState($percent, $kind = 'M') {
        if ($percent > 0.8) $state = 'brand_new';
        elseif ($percent > 0.6) $state = 'new';
        elseif ($percent > 0.4) $state = 'used';
        elseif ($percent > 0.2) $state = 'often_used';
        else $state = 'crumbling';
        return self::$states[$state][$kind];
    }

    public function getAttack() {
        return $this->attack;
    }

    /**
     * get all tools (itemtypes) which are mandatory when this type of item
     * is build
     */
    public function get_mandatory_tools() {
        
        return $this->tools->where('optional', '=', 0)->find_all();
        
    }
    
    /**
     * get optional tools (itemtypes) which may be used to speed up project 
     * when this type of item is build
     */
    public function get_optional_tools() {
        
        return $this->tools->where('optional', '=', 1)->find_all();
        
    }
    
}

?>
