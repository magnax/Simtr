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
    protected $name;

    public function setResource($itemtype) {
        $this->itemtype = $itemtype;
    }
    
    public function setName($name) {
        $this->name = $name;
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
