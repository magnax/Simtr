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

    public function before() {

        parent::before();

        /**
         * init session
         */
        $this->session = Session::instance();
        
        /**
         * get default view and set it to content variable
         */
        $page = ($this->request->directory() ? $this->request->directory() . '/' : '')
            . $this->request->controller() . '/' . $this->request->action();

        if (Kohana::find_file('views', $page)) {
            $this->template->content = View::factory($page);
            $this->view = $this->template->content;
            
        } else {
            $this->template->content = 'brakuje pliku views/'.$page.'.php :)';
        }
        
        /**
         * flash messages: error and info message
         */
        $this->template->err = $this->session->get_once('err');
        
        //flash message
        $this->template->msg = $this->session->get_once('msg');
        
        /**
         * Redis database init
         */
        try {
        
            $this->redis = new Redisent(Kohana::$config->load('database.dsn'));
            $this->template->active_count = count($this->redis->keys('active:*'));
            
        } catch (RedisException $e) {
            $this->redirectError($e->getMessage());
        }
        
    }

    /**
     * sets error flash message and redirects
     * 
     * @param string $err
     * @param string $uri
     */
    public function redirectError($err, $uri = 'error') {

        $this->session->set('err', $err);
        $this->request->redirect($uri);

    }

    /**
     * sets info flash message and redirects
     * 
     * @param string $msg
     * @param string $uri
     */
    public function redirectMessage($msg, $uri = '/') {

        $this->session->set('msg', $msg);
        Request::instance()->redirect($uri);

    }
    
}

?>
