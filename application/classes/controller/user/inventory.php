<?php

class Controller_User_Inventory extends Controller_Base_Character {

    public function before() {
        
        parent::before();
        
        $this->inventory_menu = View::factory('user/inventory/index');
    }

    public function action_index($type = 'raws') {

        $type = Session::instance()->get_once('inventory', 'raws');
        
        $types = array('all', 'raws', 'items', 'notes', 'keys', 'coins');
        if (!in_array($type, $types)) {
            $type = 'raws';
        }
        
        $action = 'action_'.$type;
        $this->$action();

    }

    public function action_all() {
        $raws = $this->character->getRaws();
        $items = $this->character->getItems();
        $notes = $this->character->getNotes();
        $this->template->content = View::factory('user/inventory/all', array(
            'raws'=>$raws,
            'items'=>$items,
            'notes'=>$notes,
            'inventory_menu'=>  $this->inventory_menu,
        ));
        Session::instance()->set('inventory', 'all');
    }

    public function action_raws() {
        $raws = $this->character->getRaws();
        $this->template->content = View::factory('user/inventory/raws', array(
            'raws'=>$raws,
            'inventory_menu'=>  $this->inventory_menu,
        ));
        Session::instance()->set('inventory', 'raws');
    }

    public function action_items() {
        $items = $this->character->getItems();
        $this->template->content = View::factory('user/inventory/items', array('items'=>$items,
            'inventory_menu'=>  $this->inventory_menu,
        ));
        Session::instance()->set('inventory', 'items');
    }

    public function action_notes() {
        $notes = $this->character->getNotes();
        $this->template->content = View::factory('user/inventory/notes', array('notes'=>$notes,
            'inventory_menu'=>  $this->inventory_menu,
        ));
        Session::instance()->set('inventory', 'notes');
    }

    public function action_keys() {
        
    }

    public function action_coins() {
        
    }
    
    public function action_put() {
        
        $item_id = $this->request->param('id');
        
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

        $event->addRecipients($this->location->getVisibleCharacters());
        $event->send();
        
        $this->request->redirect('events');
        
    }
}

?>
