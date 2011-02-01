<?php defined('SYSPATH') or die('No direct script access.');

require_once APPPATH.'Predis.php';

class Controller_Welcome extends Controller {

	public function action_index() {

		$redis = new Predis_Client(array(
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 15,
            'alias' => 'mn'
        ));

        $redis->select('mn');
        
        $redis->set('library', 'predis');
        $retval = $redis->get('library');

        $redis->save();

        $redis->select('magnax');

        $redis->set('user:name', 'Maciek');
        $redis->set('user:age', '38');

        $redis->save();

        $me = $redis->get('user:name');

        $this->request->response = 'Info: '.$me;

	}

} // End Welcome
