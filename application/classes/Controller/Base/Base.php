<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base_Base extends Controller_Template {

    /**
     *
     * @var Session Object
     */
    protected $session;
    
    /**
     *
     * @var View Object
     * 
     * shortcut to template content
     */
    protected $view;
    
    /**
     *
     * @var Redis Database Object
     */
    protected $redis;
    
    /**
     * @var string
     */
    protected $server_uri;


    public function before() {

        parent::before();

        /**
         * init session
         */
        $this->session = Session::instance();
        
        /**
         * get default view and set it to content variable
         */
        $page = ($this->request->directory() ? strtolower($this->request->directory()) . '/' : '')
            . strtolower($this->request->controller()) . '/' . $this->request->action();

        if (Kohana::find_file('views', $page)) {
            $this->template->content = View::factory($page);
            $this->view = $this->template->content;
            
        } else {
            $this->template->content = 'brakuje pliku views/'.strtolower($page).'.php :)';
        }
        
        /**
         * flash messages: error and info message
         */
        $this->template->set_global('error', $this->session->get_once('error'));
        $this->template->set_global('message', $this->session->get_once('message'));
        
        /**
         * Redis database init
         */
        $this->redis = RedisDB::instance();
        
        $this->template->set_global('active_count', count($this->redis->keys('active:*')));
        
        $this->server_uri = Kohana::$config->load('general.server_ip');
        $this->template->set_global('server_uri', $this->server_uri);
        
    }

    /**
     * sets error flash message and redirects
     * 
     * @param string $err
     * @param string $uri
     */
    public function redirectError($err, $uri = 'error') {

        Session::instance()->set('error', $err);
        $this->redirect($uri);

    }

    /**
     * sets info flash message and redirects
     * 
     * @param string $msg
     * @param string $uri
     */
    public function redirectMessage($msg, $uri = '/') {

        $this->session->set('message', $msg);
        $this->redirect($uri);

    }
    
}

?>
