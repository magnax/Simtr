<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Param extends Controller_Base_User {
    
    public function action_index() {
        
        $e = new Model_Event();
        $e->add('params', array('name' => 'sndr', 'value' => 23));
        //print_r($e->params);
        //$e->save();
//        $e->type = "EnterLocation";
        print_r($e);
        echo '<p>';
        //print_r($e->params);
//        $e->save();
//        print_r($e);
        
    }
    
}

?>
