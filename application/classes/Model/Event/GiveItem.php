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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $item = ORM::factory('Item')->where('id', '=', $this->itemid)
            ->find();
        
        $returned['stt'] = Model_ItemType::getState($item->points / $item->itemtype->points, $item->itemtype->kind);
        $returned['itemid'] = $item->itemtype->name;
        
        return $returned;
        
    }    
    
    public function send() {}

}

?>