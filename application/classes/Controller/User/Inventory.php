<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Inventory extends Controller_Base_Character {

    public function before() {
        parent::before();
        $this->template->set_global('inventory_menu', View::factory('user/inventory/index'));
    }
    
    public function after() {
        parent::after();
        Session::instance()->set('inventory', $this->request->action());
    }

    public function action_index($type = 'raws') {

        $type = Session::instance()->get('inventory', 'raws');
        
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
        ));
    }

    public function action_raws() {
        $raws = $this->character->getRaws();
        $this->template->content = View::factory('user/inventory/raws', array(
            'raws'=>$raws,
        ));
    }

    public function action_items() {
        $items = $this->character->getItems();
        $this->template->content = View::factory('user/inventory/items', array(
            'items'=>$items,
        ));
    }

    public function action_notes() {
        $notes = $this->character->getNotes();
        $this->template->content = View::factory('user/inventory/notes', array(
            'notes'=>$notes,
        ));
    }

    public function action_keys() {
        
    }

    public function action_coins() {
        
    }
    
    public function action_put() {
        
        $item_id = $this->request->param('id');
        
        $item = new Model_Item($item_id);
        
        try {

            $this->character->drop_item($item, $this->location);

            //wysłanie eventu
            $event = new Model_Event();
            $event->type = Model_Event::PUT_ITEM;
            $event->date = $this->game->getRawTime();

            $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
            $event->add('params', array('name' => 'item_id', 'value' => $item->id));
            $event->add('params', array('name' => 'item_points', 'value' => $item->itemtype->kind . $item->points_percent()));

            $event->save();

            $event->notify($this->location->getVisibleCharacters());
            
        } catch (Exception $e){
            
            $this->redirectError($e->getMessage(), 'user/inventory/items');
            
        }
        
        $this->redirect('user/inventory');
        
    }
    
    public function action_give() {
    
        $item_id = $this->request->param('id');
        $item = new Model_Item($item_id);
        
        if (!$this->character->hasItem($item_id)) {
            $this->redirectError('Nie posiadasz tego przedmiotu!', 'events');
        }
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $recipient = new Model_Character($this->request->post('character_id'));

            try {

                $this->character->give_item($item, $recipient);

                //wysłanie eventu
                $event = new Model_Event();
                $event->type = Model_Event::GIVE_ITEM;
                $event->date = $this->game->getRawTime();

                $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
                $event->add('params', array('name' => 'rcpt', 'value' => $recipient->id));
                $event->add('params', array('name' => 'item_id', 'value' => $item->id));
                $event->add('params', array('name' => 'item_points', 'value' => $item->itemtype->kind . $item->points_percent()));

                $event->save();

                $event->notify($this->location->getVisibleCharacters());

            } catch (Exception $e){

                $this->redirectError($e->getMessage(), 'user/inventory/items');

            }
            
            $this->redirect('events');
            
        }

        $characters = $this->location->getHearableCharacters();
        $this->view->characters = $this->character->charactersSelect($characters, false);

        $this->view->item = $item;
        
    }
    
}

?>
