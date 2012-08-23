<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Base_Base extends Controller_Template {

    /**
     *
     * @var Session Object
     */
    protected $session;

    /**
     *
     * @var Redis Database Object
     */
    protected $redis;
    
    /**
     *
     * @var View Object
     * 
     * shortcut to template content
     */
    protected $view;

    /**
     * @var GameTime Object
     * 
     * overall game time object
     */
    protected $game;

    public function before() {

        parent::before();

        /**
         * init session
         */
        $this->session = Session::instance();

        /**
         * check time daemon existence and get current game time
         */
        try {
            $this->game = new Model_GameTime(Kohana::$config->load('general.paths.time_daemon_path'));           
        } catch (Exception $e) {
            $this->redirectError($e->getMessage());
        }

        $this->template->current_time = $this->game->getTime();
        $this->template->current_date = $this->game->getDate();
        
        /**
         * get default view and set it to content variable
         */
        $page = ($this->request->directory() ? $this->request->directory() . '/' : '')
            . $this->request->controller() . '/' . $this->request->action();

        if (Kohana::find_file('views', $page)) {
            $this->template->content = View::factory($page);
        } else {
            $this->template->content = 'brakuje pliku views/'.$page.'.php :)';
        }

        $this->view = $this->template->content;

        /**
         * flash messages: error and info message
         */
        $this->template->err = $this->session->get_once('err');
        
        //flash message
        $this->template->msg = $this->session->get('msg');
        $this->session->delete('msg');
        
        /**
         * Redis database init
         */
        $this->redis = new Redisent(Kohana::$config->load('database.dsn'));

        try {
            $this->template->active_count = count($this->redis->keys('active:*'));
        } catch (Predis_CommunicationException $e) {
            $this->redirectError('Server Redis nie uruchomiony');
        }
        
    }

    /**
     * sets error flash message and redirects
     * 
     * @param string $err
     * @param string $uri
     */
    public function redirectError($err, $uri = 'base/error') {

        $this->session->set('err', $err);
        Request::instance()->redirect($uri);

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
