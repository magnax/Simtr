<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Administering characters
 * 
 * @method action_menu displays main character menu
 * @method action_random_skills randomizing some characters attributes
 * 
 * 
 * 
 */

class Controller_Admin_Characters extends Controller_Base_Admin {
    
    public function action_index() {
        
        $characters = ORM::factory('Character')->find_all();
        
        $this->template->content = View::factory('admin/characters/index')
            ->set('count', ORM::factory('Character')->count_all())
            ->bind('characters', $characters);
        
    }
    
    /**
     * randomizing some characters parameters
     */
    public function action_skills() {
        
        $character = new Model_Character($this->request->param('id'));
        
        $character->check_skills();
        
        $skills = ORM::factory('CharacterSkill')->where('character_id', '=', $character->id)->find_all()->as_array();
        
        $this->template->content = View::factory('admin/characters/skills')
            ->bind('character', $character)
            ->bind('skills', $skills);
        
    }
    
    public function action_add_initial_skills() {
        
        $character = new Model_Character($this->request->param('id'));
        
        $character->add_initial_skills();
        
        $this->redirect('admin/characters/skills/' . $character->id);
        
    }
    
    public function action_edit($user_id) {
        
        $user = json_decode($this->redis->get("characters:$user_id"), true);
        
        if (isset($_POST['save'])) {
            
            $user['name'] = $_POST['name'];
            $user['life'] = $_POST['life'];
            $this->redis->set("characters:$user_id", json_encode($user));
            $this->redirect('/admin/characters/all');
            
        }
        
        $this->view->raws = array();
        
        $raws = $this->redis->get("raws:$user_id");
        if ($raws) {
            $raw_data = json_decode($raws);
            foreach ($raw_data as $k=>$v) {
                $this->view->raws[] = array(
                    'id' => $k,
                    'amount' => $v,
                );
            }
        }
        
        //items:
        $this->view->items = array();
        
        $items = $this->redis->smembers("char_items:$user_id");
        if ($items) {
            foreach ($items as $item) {
                $item_data = json_decode($this->redis->get("global:items:$item"), true);
                $itemtype = json_decode($this->redis->get("itemtype:{$item_data['type']}"), true);
                $this->view->items[] = array(
                    'id' => $item_data['id'],
                    'type' => $itemtype['name'],
                    'points' => $item_data['points'],
                );
            }
        }
        
        $this->view->user = $user;
        
        $itemtype = array();
        
        //typy przedmiotów do listy
        $ids_itemtypes = $this->redis->keys("itemtype:*");
        
        foreach ($ids_itemtypes as $type) {
            $arr = explode(':', $type);
            $id = $arr[1];
            if ($id != 0 && $id != 1) {
                $itemtype_data = json_decode($this->redis->get("itemtype:$id"), true);
                $itemtype[$id] = $itemtype_data['name']. ' ('.$itemtype_data['points'].')';
            }
        }
        
        $this->view->itemtypes = $itemtype;
        
    }
    
    public function action_addraw($character_id) {
        
        $raws = $this->redis->get("raws:$character_id");
        
        if ($raws) {
            $raws = json_decode($raws, true);
            if (in_array($_POST['id'], array_keys($raws))) {
                $raws[$_POST['id']] += $_POST['amount'];
            } else {
                $raws[$_POST['id']] = $_POST['amount'];
            }
        } else {
            $raws[$_POST['id']] = $_POST['amount'];
        }
        
        $this->redis->set("raws:$character_id", json_encode($raws));
       
        $this->redirect('/admin/characters/edit/'.$character_id);
        
    }
    
    public function action_additem($character_id) {
        
        $item = array();
        $item['id'] = $this->redis->incr("global:IDItem");
        $item['type'] = $_POST['type'];
        $item['points'] = $_POST['points'];
        
        $this->redis->set("global:items:{$item['id']}", json_encode($item));
        $this->redis->sadd("char_items:$character_id", $item['id']);
        
        $this->redirect('/admin/characters/edit/'.$character_id);
    }
    
}

?>
