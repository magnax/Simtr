<?php

class Controller_Admin_Dump extends Controller {

    //dumping users to separate json file
    public function action_test() {
        
        $this->redis = new Predis_Client('redis://magnax:4cd3a93c90d60288117ec4cadf8c0aaa@50.30.35.9:2693/?password=4cd3a93c90d60288117ec4cadf8c0aaa');

        try {
            $size = $this->redis->dbsize();
            echo 'OK: '.$size;
        } catch (Predis_CommunicationException $e) {
            echo 'Server Redis nie uruchomiony';
        }
       
    }
    
    public function action_testshell() {
        
        $output = shell_exec('pwd');
        echo $output;
        
    }
 
        
    public function action_testd() {
        
        $output = shell_exec('python ./simtrd/d.py say');
        echo $output;
        
    }
    
}

?>
