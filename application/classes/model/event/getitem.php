<?php

class Model_Event_GetItem extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia GET_ITEM
     */

    /**
     * identyfikator przedmiotu
     *
     * @var <type> int
     */
    protected $item_id;

    public function setItem($item_id) {
        $this->item_id = $item_id;
    }

    public function toArray() {

        $arr = parent::toArray();

        $arr['itemid'] = $this->item_id;
        $arr['sndr'] = $this->sender;

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
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>