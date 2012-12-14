<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_GiveItem extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia GIVE_ITEM
     */

    /**
     * identyfikator przedmiotu
     *
     * @var <type> int
     */
    protected $item_id;
    
    protected $rcpt;

    public function setItem($item_id) {
        $this->item_id = $item_id;
    }

    public function setRecipient($character_id) {
        $this->rcpt = $character_id;
    }
    
    public function toArray() {

        $arr = parent::toArray();

        $arr['itemid'] = $this->item_id;
        $arr['sndr'] = $this->sender;
        $arr['rcpt'] = $this->rcpt;

        return $arr;

    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $item = ORM::factory('item')->where('id', '=', $event_data['itemid'])
            ->find();
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        $returned['stt'] = Model_ItemType::getState($item->points / $item->itemtype->points, $item->itemtype->kind);
        $returned['itemid'] = $item->itemtype->name;
        
        if (in_array('rcpt', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['rcpt'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['rcpt'], $lang);
            }
            $returned['rcpt'] = '<a href="chname?id='.
                $event_data['rcpt'].'">'.$name.'</a>';
        }
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>