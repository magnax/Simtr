<?php  defined('SYSPATH') or die('No direct script access.');

/**
 * kontroler dla administratorów
 */
class Controller_Base_Admin extends Controller_Template {

    public $template = 'templates/admin';

    protected $redis = null;

    public function before() {
        parent::before();
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
        $this->redis = new Redis();
        $this->redis->connect('127.0.0.1:6379');

        try {
            $this->redis->dbsize();
        } catch (Predis_CommunicationException $e) {
            $this->redirectError('Server Redis nie uruchomiony');
        }
    }
}

?>