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
            $this->template->character = $this->character->get_info($this->raw_time);
        }
        
        //redirect if no current character
        if (!$this->character) {
            Request::current()->redirect('user/menu');
        }
        
        $this->location = ORM::factory('location', $this->character->location_id);
        
        //get character object with character and locations names
//        $this->character = Model_Character::getInstance($this->redis)
//            ->fetchOne($this->user->getCurrentCharacter());
//        
//        //nie jest konieczne, bo domyślny lang to 'pl' właśnie
//        $this->character->lang = 'pl';
//        $this->character->raw_time = $this->game->raw_time;
//        $this->character->fetchChnames();
//        
//        //get location
//        $this->location = Model_LocationFactory::getInstance($this->redis)
//            ->fetchOne($this->character->location_id);
//        
//        $ch = $this->character->toArray();
//
//        $ch['spawn_day'] =
//            Model_GameTime::formatDateTime($this->character->getSpawnDate(), 'd');
//        if ($ch['project_id']) {
//            $project = Model_ProjectManager::getInstance(null, $this->redis)
//                ->findOneByID($ch['project_id'])->getProject();
//            $ch['myproject'] = array(
//                'name'=>$this->dict->getString($project->getName()),
//                'percent'=>$project->getPercent(1)
//            );
//        }
//        $ch['age'] = $this->character->countAge($this->game->raw_time);
//        $name = $this->character->getLname($ch['location_id']);
//        $ch['location'] = $name ? $name : Model_Dict::getInstance($this->redis)->getString('unknown_location');
//        $name = $this->character->getLname($ch['spawn_location_id']);
//        $ch['spawn_location'] = $name ? $name : Model_Dict::getInstance($this->redis)->getString('unknown_location');
//        $ch['known_as'] = $this->character->getChname($ch['id']);
//
//        $this->template->character = $ch;

        

    }

}

?>
