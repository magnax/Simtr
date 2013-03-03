<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Events extends Controller_Base_Admin {
    
    public function action_index() {
        
        $all_events = $this->redis->keys("events:*");
        //print_r($all_events);
        $events = array();
        foreach ($all_events as $event) {
            $id = str_replace('events:', '', $event);
            $events[$id] = RedisDB::getJSON($event);
        }
        krsort($events);
        $this->view->events = $events;
        
    }
    
    public function action_edit() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            RedisDB::set("events:{$this->request->param('id')}", $this->request->post('event'));
            $this->redirect('/admin/events');
        }
        $event = RedisDB::get("events:{$this->request->param('id')}");
        $this->view->event = $event;
        
    }
    
}

?>
