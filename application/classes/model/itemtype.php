<?php defined('SYSPATH') or die('No direct script access.');

class Model_ItemType extends ORM {
    
//    protected $source;
//    
//    protected $attack;
//
//
//    public function __construct($source) {
//        $this->source = $source;
//    }
//    
//    static public function getInstance($source) {
//        if ($source instanceof Redis) {
//            return new Model_ItemType_Redis($source);
//        }
//    }
    
    public static function getState($percent) {
        if ($percent > 0.8) return 'brand_new';
        elseif ($percent > 0.6) return 'new';
        elseif ($percent > 0.4) return 'used';
        elseif ($percent > 0.2) return 'often_used';
        else return 'crumbling';
    }

    public function getAttack() {
        return $this->attack;
    }

//    abstract public function fetchOne($itemtype_id, $as_array = false);
//    abstract public function getName($item_id);
    
}

?>
