<?php

class Controller_Admin_System extends Controller_Base_Admin {

    public function action_index() {
        
        $node_query = 'forever list';
        
        $result = shell_exec($node_query);
        var_dump($result);
        
        $this->view->node_status = $result;
        
    }
    
}

?>
