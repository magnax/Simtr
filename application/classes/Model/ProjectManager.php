<?php defined('SYSPATH') or die('No direct script access.');

abstract class Model_ProjectManager {

    protected $_project;
    protected $source;

    private function  __construct($project, $source) {
        $this->_project = $project;
        $this->source = $source;
    }

    public static function getInstance($project, $source = null) {
        if ($project && ($project instanceof Model_Project)) {
            $source = $project->getSource();
        }
        //if ($source instanceof Redisent) {
        if ($source instanceof RedisDB) {
            return new Model_ProjectManager_Redis($project, $source);
        }
    }

    public function getProject() {
        return $this->_project;
    }

    public function getId() {
        return $this->_project->getId();
    }

    public function addParticipant(Model_Character $character, $time) {
        $this->_project->addParticipant($character, $time);
    }

    public function removeParticipant(Model_Character $character, $time) {
        $this->_project->removeParticipant($character, $time);
    }

    public function getPercent($accuracy) {
        $this->_project->getPercent($accuracy);
    }

    public function set(array $data) {
        $this->_project->set($data);
    }
    
    abstract public function save();
    abstract public function findOneById($id);

}

?>
