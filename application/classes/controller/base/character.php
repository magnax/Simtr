<?php defined('SYSPATH') or die('No direct script access.');

/**
 * bazowy kontroler postaci
 * zawsze musi istnieć bieżąca postać usera
 */
class Controller_Base_Character extends Controller_Base_User {

    public $template = 'templates/character';
    
    /**
     * current character
     */
    protected $character = null;
    
    /**
     * current location
     */
    protected $location;
    
    protected $lang = 'pl';

    public function before() {
        
        parent::before();
        
        if ($this->session->get('current_character')) {
            $this->character = ORM::factory('character', $this->session->get('current_character'));
            $this->template->set_global('character', $this->character->get_info($this->raw_time));
        }
        
        //redirect if no current character
        if (!$this->character) {
            Request::current()->redirect('user/menu');
        }
        
        //redirect if character is dead
        if (!$this->character->life && !(($this->request->action() == 'index') && ($this->request->controller() == 'events'))) {            
            $this->redirectError('Jesteś martwy a martwi zazwyczaj nic już nie robią!', 'events');
        }
        
        $this->location = ORM::factory('location', $this->character->location_id);        

    }

}

?>
