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
    
    public function action_menu() {
        
    }
    
    /**
     * randomizing some characters parameters
     */
    public function action_random_skills() {
        
        $users_ids = $this->redis->smembers("global:characters");
        
        foreach ($users_ids as $user_id) {
            
            //read character
            $user = json_decode($this->redis->get("characters:$user_id"), true);
            
            //vitality 800-1200
            $user['vitality'] = rand(800, 1200);
            //current life set to character vitality
            $user['life'] = $user['vitality'];
            //strength 0.6 - 1.8
            $user['strength'] = rand(6, 18) / 10;
            //fighting skill 0.8 - 1.2
            $user['fighting'] = rand(8, 12) / 10;
            
            //save character
            $this->redis->set("characters:$user_id", json_encode($user));
            
        }
        
        //redirect to characters menu
        $this->redirect('/admin/characters/menu');
        
    }


    public function action_correct() {
        $users = $this->redis->keys("characters:*");
        foreach ($users as $u) {
            $n = explode(':', $u);
            if (strpos($u, 'lnames') !== false) {
                $this->redis->set("lnames:{$n[1]}:{$n[3]}", $this->redis->get($u));
                $this->redis->del($u);
            } elseif (strpos($u, 'chnames') !== false) {
                $this->redis->set("chnames:{$n[1]}:{$n[3]}", $this->redis->get($u));
                $this->redis->del($u);
            }
            $this->redis->sadd('global:characters', $n[1]);
        }
        
        //redirect to characters menu
        $this->redirect('/admin/characters/menu');
        
    }
    
    public function action_all() {
        
        $users_id = $this->redis->smembers('global:characters');
        $this->view->users = array();
        
        foreach ($users_id as $user) {
            $user_data = json_decode($this->redis->get("characters:$user"), true);
            $this->view->users[] = array(
                'id' => $user,
                'name' => isset($user_data['name']) ? $user_data['name'] : '---',
                'sex' => isset($user_data['sex']) ? $user_data['sex'] : '---',
            );
        }
        
        asort($this->view->users);
        
    }
    
    public function action_del($user_id) {
        
        $this->redis->del("characters:$user_id");
        $this->redis->del("characters:$user_id:events");
        $this->redis->del("characters:$user_id:equipment:raws");
        $this->redis->srem("global:characters", $user_id);
        
        $this->redirect('/admin/characters/all');
        
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
