<?php

class Controller_Admin_ItemTypes extends Controller_Base_Admin {
    
    public function action_menu() {
        
    }
    
    public function action_all() {
        
        $all_itemtypes = array();
        
        $all_itemtypes_keys = $this->redis->keys('itemtype:*');
        
        foreach ($all_itemtypes_keys as $key) {
            
            $arr = explode(':', $key);
            $id = $arr[1];
            $itemtype_data = json_decode($this->redis->get($key), true);
            $all_itemtypes[] = array(
                'id' => $id,
                'name' => $itemtype_data['name'],
                'attack' => $itemtype_data['attack'],
                'shield' => $itemtype_data['shield'],
                'weight' => $itemtype_data['weight'],
                'visible' => $itemtype_data['visible'],
                'points' => $itemtype_data['points'],
                'rot' => $itemtype_data['rot'],
                'rot_use' => $itemtype_data['rot_use'],
                'repair' => $itemtype_data['repair'],
            );
        }
        
        $this->view->itemtypes = $all_itemtypes;
        
    }
    
    public function action_add() {
        $this->view->itemtype = array('name'=>'', 'attack'=>'');
    }
    
    public function action_edit($id) {        
        $this->view->itemtype = json_decode($this->redis->get("itemtype:$id"), true);
    }
    
}

?>
