<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Fixtures extends Controller_Base_Admin {

    private $redis_keys = array(
        'event_tpl',
        'dict',
    );

    public function action_menu() {
        $this->view->redis_keys = $this->redis_keys;
    }

    public function action_load() {

        echo 'Ładowanie...<br />';
        $loaded = 0;

        foreach ($this->redis_keys as $rediskey){

            if (isset ($_POST[$rediskey]) && $_POST[$rediskey] == 'on') {
                unset($data);
                include APPPATH.'config/fixtures/'.$rediskey.'.php';
                if (isset($data)) {
                    foreach ($data as $key => $val) {
                        if ($this->redis->set("$rediskey:$key", json_encode($val))) {
                            $loaded++;
                        }
                    }
                }
                echo $rediskey.'...OK<br />';

            }
        }

        echo ' załadowano: '.$loaded.'.';

    }

}

?>
