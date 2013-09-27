<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Keys extends Controller_Base_Admin {
    
    //dump all keys in database
    public function action_index() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            $pattern = $this->request->post('pattern');
            if (!strlen($pattern)) {
                $pattern = '*';
            }
            Session::instance()->set('pattern', $pattern);
        } else {
            $pattern = Session::instance()->get('pattern', '*');
        }
        
        $keys = $this->redis->keys($pattern);
        asort($keys);
        
        $this->view->keys = $keys;
        $this->view->pattern = $pattern;
    }
    
    public function action_delete() {
        
        RedisDB::del($this->request->param('id'));
        $this->redirect('admin/keys');
        
    }
    
    public function action_show() {
        
        $key = $this->request->param('id');
        
        $type = RedisDB::type($key);
        
        switch ($type) {
            case 'set':
                $value = RedisDB::smembers($key);
                $value = join("\n", $value);
                break;
            case 'string':
                $value = RedisDB::get($key);
                break;
            case 'list':
                $value = RedisDB::lrange($key, 0, -1);
                $value = join("\n", $value);
                break;
            case 'hash':
                $value = RedisDB::hgetall($key);
                $last = null;
                $h = array();
                foreach ($value as $v) {
                    if (!$last) {
                        $h[$v] = 'xxx';
                        $last = $v;
                    } else {
                        $h[$last] = $v;
                        $last = null;
                    }
                }
                $value = http_build_query($h);
                $value = str_replace('&', "<br>", $value);
                $value = str_replace('=', " = ", $value);
                break;
        }
        
        $this->template->content = View::factory('admin/keys/show')
                ->set('type', $type)
                ->set('key', $this->request->param('id'))
                ->bind('value', $value);
        
    }
    
}

?>
