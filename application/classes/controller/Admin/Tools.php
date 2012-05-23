<?php

/**
 * Manufacturing tools controller  
 */
class Controller_Admin_Tools extends Controller_Base_Admin {
    
    /**
     * list of tools
     */
    public function action_index() {
        $this->view->tools = Model_Item::getInstance($this->redis)->
            fetchAll($filter = Model_Item::TOOL);
    }
    
    /**
     * add new tool 
     */
    public function action_add() {
        
    }
    
}

?>
