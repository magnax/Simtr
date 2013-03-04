<?php defined('SYSPATH') or die('No direct script access.');

class Model_Event_BuildEnd extends Model_Event {

    /**
     * właściwości specyficzne dla zdarzenia BUILD_END
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

    public function dispatchArgs(array $args, Model_Character $character, $lang) {
        
        $returned = parent::dispatchArgs($args, $character, $lang);
        
        $returned['name'] = $this->name;
        $returned['itemtypeid'] = $this->itemtypeid;

        return $returned;
        
    }
    
    public function send() {}

}

?>
