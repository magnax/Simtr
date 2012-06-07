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

    public function dispatchArgs($event_data, $args, $character) {
        
//        $item = Model_Resource::getInstance($this->source)
//            ->fetchOne($event_data['item_id']);
        
        $returned = array();
        
        if (in_array('sndr', $args)) {
            $name = $character->getChname($event_data['sndr']);
            if (!$name) {
                $name = $character->getUnknownName($event_data['sndr']);
                $name = Model_Dict::getInstance($this->source)->getString($name);
            }
            $returned['sndr'] = '<a href="/user/char/nameform/'.
                $event_data['sndr'].'">'.$name.'</a>';
        }
        
        $dict = Model_Dict::getInstance($this->source);
        $lang = $dict->getLang();
        
        $item = json_decode($this->source->get("global:items:{$event_data['itemid']}"), true);
        $itemtype = json_decode($this->source->get("itemtype:{$item['type']}"), true);
        $itemkind = $this->source->get("kind:$lang:{$itemtype['name']}");
        if (!$itemkind) {
            $itemkind = 'm';
        }
        $state = Model_ItemType::getInstance($this->source)
            ->getState($item['points'] / $itemtype['points']).":$itemkind";
        
        $returned['stt'] = Model_Dict::getInstance($this->source)->getString($state);
        $returned['itemid'] = Model_Dict::getInstance($this->source)->getString($itemtype['name']);
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>