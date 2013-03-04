<?php

class Controller_User_Build extends Controller_Base_Character {

    public function action_index() {
        
        $menu_id = $this->request->param('menu_id');
        
        $menu = Model_Buildmenu::getMenu(NULL);
        $this->view->menu = $menu;
        $this->view->submenu = array(
            'id' => $menu_id,
            'menu' => $menu_id ? Model_Buildmenu::getMenu($menu_id) : null,
            'items' => $menu_id ? Model_Spec::getItems($menu_id) : null,
        );
        //print_r($this->view->submenu);
    }  

}

?>
