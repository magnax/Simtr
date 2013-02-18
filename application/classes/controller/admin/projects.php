<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_Projects extends Controller_Base_Admin {
    
    public function action_index() {
        
        $all_projects = $this->redis->keys("projects:*");
        $events = array();
        foreach ($all_projects as $project) {
            $p = explode(':', $project);
            if (count($p) == 2) {
                //only projects definitions not participants or workers
                $id = str_replace('projects:', '', $project);
                $projects[$id] = RedisDB::getJSON($project);
            }
        }
        krsort($projects);
        $this->view->projects = $projects;
        
    }
    
    public function action_edit() {
        
        if (HTTP_Request::POST == $this->request->method()) {
            RedisDB::set("projects:{$this->request->param('id')}", $this->request->post('project'));
            $this->request->redirect('/admin/projects');
        }
        $project = RedisDB::get("projects:{$this->request->param('id')}");
        $this->view->project = $project;
        
    }
    
}

?>