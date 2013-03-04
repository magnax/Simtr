<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_MakeEnd extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia MAKE_END
     */

    /**
     * identyfikator typu przedmiotu
     *
     * @var <type> int
     */
    protected $itemtype_id;

    public function setResource($itemtype, $amount) {
        $this->itemtype = $itemtype;
        $this->amount = $amount;
    }

    public function dispatchArgs($event_data, $args, $character_id, $lang) {
        
        $returned = array();
        
        $returned['name'] = $event_data['name'];
        $returned['itemtypeid'] = $event_data['itemtypeid'];
        
        if (in_array('sndr', $args)) {
            $name = ORM::factory('chname')->name($character_id, $event_data['sndr'])->name;
            if (!$name) {
                $name = ORM::factory('character')->getUnknownName($event_data['sndr'], $lang);
            }
            $returned['sndr'] = '<a href="chname?id='.
                $event_data['sndr'].'">'.$name.'</a>';
        }

        return $returned;
        
    }
    
    public function send() {}

}

?>
