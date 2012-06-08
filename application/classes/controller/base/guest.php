<?php defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler dla niezalogowanych userów
 */
class Controller_Base_Guest extends Controller_Template {

    public $template = 'templates/guest';   
    
    public function before() {
        
        parent::before();
        
        $this->session = Session::instance();

        //sprawdzenie demona i odczytanie czasu
        try {
            $this->game = new Model_GameTime();
        } catch (Exception $e) {
            $this->redirectError($e->getMessage());
        }

        $this->template->current_time = $this->game->getTime();
        $this->template->current_date = $this->game->getDate();
        
        //load default view
        $page = ($this->request->directory ? $this->request->directory.'/' : '')
            . $this->request->controller.'/'.$this->request->action;

        if (Kohana::find_file('views', $page)) {
            $this->template->content = View::factory($page);
        } else {
            $this->template->content = 'brakuje pliku views/'.$page.'.php :)';
        }
        
        $this->view = $this->template->content;
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

    public function redirectError($err, $uri = 'error') {

        $this->session->set('err', $err);
        Request::instance()->redirect($uri);

    }

    public function redirectMessage($msg, $uri = '/') {

        $this->session->set('msg', $msg);
        Request::instance()->redirect($uri);

    }
    
}

?>
