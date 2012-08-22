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
     * cały obiekt gry (m.in. czas)
     */
    protected $game;

    public function before() {

        parent::before();

        /**
         * inicjalizacja sesji
         */
        $this->session = Session::instance();

        echo Kohana::config('general.paths.time_daemon_path');

        //sprawdzenie demona i odczytanie czasu
        try {
            $this->game = new Model_GameTime(Kohana::config('general.paths.time_daemon_path'));
            
        } catch (Exception $e) {
            $this->redirectError($e->getMessage());
        }

        $this->template->current_time = $this->game->getTime();
        $this->template->current_date = $this->game->getDate();
            //Model_GameTime::formatDateTime($this->game->raw_time, 'y/f (d)');
        
        $page = ($this->request->directory ? $this->request->directory.'/' : '')
            . $this->request->controller.'/'.$this->request->action;

        if (Kohana::find_file('views', $page)) {
            $this->template->content = View::factory($page);
        } else {
            $this->template->content = 'brakuje pliku views/'.$page.'.php :)';
        }

        $this->view = $this->template->content;

        /**
         * jeśli jest błąd
         */
        $this->template->err = $this->session->get('err');
        $this->session->delete('err');
        
        //flash message
        $this->template->msg = $this->session->get('msg');
        $this->session->delete('msg');
        
        /**
         * inicjalizacja i połączenie z Redisem
         * parametry połączenia w application/config/database.php
         */
        //$this->redis = new Predis_Client(Kohana::config('database.dsn'));
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1');

        try {
            $this->template->active_count = count($this->redis->keys('active:*'));
        } catch (Predis_CommunicationException $e) {
            $this->redirectError('Server Redis nie uruchomiony');
        }
        
    }

    public function redirectError($err, $uri = 'base/error') {

        $this->session->set('err', $err);
        Request::instance()->redirect($uri);

    }

    public function redirectMessage($msg, $uri = '/') {

        $this->session->set('msg', $msg);
        Request::instance()->redirect($uri);

    }
    
}

?>
