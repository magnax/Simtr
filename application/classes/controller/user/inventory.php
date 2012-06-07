<?php

class Controller_User_Inventory extends Controller_Base_Character {

    public function action_index($type = 'raws') {

        $types = array('raws', 'items', 'notes', 'keys', 'coins');
        if (!in_array($type, $types) && $type != 'all') {
            $type = 'raws';
        }

        if ($type == 'all') {
            foreach ($types as $t) {
                $action = 'action_'.$t;
                $this->$action();
            }
        } else {
            $action = 'action_'.$type;
            $this->$action();
        }

    }

    public function action_raws() {
        $raws = $this->character->getRaws();
        $this->template->content = View::factory('user/inventory/raws', array('raws'=>$raws));
    }

    public function action_items() {
        $this->view->items = $this->character->getItems();
    }

    public function action_notes() {
        
    }

    public function action_keys() {
        
    }

    public function action_coins() {
        
    }
    
    public function action_put($item_id) {
        
        /**
         * @todo check if user has this item 
         * @todo check if user has enough place in inventory
         */
        $this->character->putItem($item_id);
        $this->location->addItem($item_id);
        //generate event
        $event = Model_EventSender::getInstance(
            Model_Event::getInstance(
                Model_Event::PUT_ITEM, $this->game->raw_time, $this->redis
            )
        );

        $event->setSender($this->character->getId());
        $event->setItem($item_id);

        $event->addRecipients($this->location->getAllVisibleCharacters($this->character->getPlaceType()));
        $event->send();
        
        $this->request->redirect('user/event');
        
    }
}

?>
