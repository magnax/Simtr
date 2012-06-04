<?php

class Controller_Admin_Characters extends Controller_Base_Admin {
    
    public function action_menu() {
        
    }
    
    public function action_random_skills() {
        $users_ids = $this->redis->smembers("global:characters");
        foreach ($users_ids as $user_id) {
            $user = json_decode($this->redis->get("characters:$user_id"), true);
            //vitality 800-1200
            $user['vitality'] = rand(800, 1200);
            //strength 0.6 - 1.8
            $user['strength'] = rand(6, 18)/10;
            //fighting skill 0.8 - 1.2
            $user['fighting'] = rand(8, 12)/10;
            $this->redis->set("characters:$user_id", json_encode($user));
        }
        print_r($users_ids);
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
        Request::instance()->redirect('/admin/characters/menu');
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
        
        $this->request->redirect('/admin/characters/all');
        
    }
    
    public function action_edit($user_id) {
        
        $user = json_decode($this->redis->get("characters:$user_id"), true);
        
        if (isset($_POST['save'])) {
            
            $user['name'] = $_POST['name'];
            $this->redis->set("characters:$user_id", json_encode($user));
            $this->request->redirect('/admin/characters/all');
            
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
        
        $this->view->user = $user;
        
    }
    
    public function action_addraw($user_id) {
        
        $raws = $this->redis->get("raws:$user_id");
        
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
        
        $this->redis->set("raws:$user_id", json_encode($raws));
       
        $this->request->redirect('/admin/characters/edit/'.$user_id);
        
    }
}

?>
