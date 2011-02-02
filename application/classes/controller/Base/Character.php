<?php

/**
 * bazowy kontroler postaci
 * zawsze musi istnieć bieżąca postać usera
 */
class Controller_Base_Character extends Controller_Base_User {

    protected $character;
    protected $location;
    protected $lnames;
    protected $chnames;
    protected $dict;
    protected $myproject;

    public function before() {
        parent::before();

        //init translations
        $this->dict = Model_Dict::getInstance($this->redis);

        //init lNames:
        $this->lnames = Model_LNames::getInstance($this->redis, $this->dict);

        //init characters names
        $this->chnames = Model_ChNames::getInstance($this->redis, $this->dict);


        $this->character = 
            Model_Character::getInstance($this->redis)
                ->fetchOne($this->user->getCurrentCharacter());

        $this->character->countAge($this->game->getRawTime());

        $ch = $this->character->toArray();

        $ch['spawn_day'] =
            Model_GameTime::formatDateTime($this->character->getSpawnDate(), 'd');
        if ($ch['project_id']) {
            $project = Model_Project::getInstance($this->redis)->findOneByID($ch['project_id']);
            $ch['myproject'] = array(
                'name'=>$project->getName(),
                'percent'=>$project->getPercent(1)
            );
        }

        $ch['location'] = $this->lnames->getName($ch['id'], $ch['location_id']);
        $ch['spawn_location'] = $this->lnames->getName($ch['id'], $ch['spawn_location_id']);
        $kn = $this->chnames->getName($ch['id'], $ch['id']);
        $ch['known_as'] = $kn ? $kn : $ch['name'];

        $this->template->character = $ch;

        $this->location = Model_Location::getInstance($this->redis)
            ->findOneByID($ch['location_id'], $ch['id']);

    }

}

?>
