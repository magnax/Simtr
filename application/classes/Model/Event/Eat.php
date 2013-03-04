<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_Eat extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia EAT
     */
    protected $desc;
    
    public function setDesc($desc) {
        $this->desc = $desc;
    }

    public function toArray() {

    $arr = parent::toArray();

    $arr['sndr'] = $this->sender;
    $arr['desc'] = $this->desc;

    return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('ChName')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('Character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        if (in_array('desc', $args)) {
            $deserialized = json_decode($event_data['desc'], true);
            if (is_array($deserialized)) {
                $ret = array();
                //tablica ID_RESOURCE => AMOUNT
                foreach ($deserialized as $k=>$v) {
                    $res = new Model_Resource($k);
                    $ret[] = $v.' gram '.$res->d;
                }
                $returned['desc'] = join(', ', $ret);
            } else {
                $returned['desc'] = $event_data['desc'];
            }
        }
        
        return $returned;
        
    }
    
    public function send() {}

}

?>