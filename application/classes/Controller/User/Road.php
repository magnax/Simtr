<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Road extends Controller_Base_Character {
    
    public function action_upgrade() {
        
        $road = new Model_Road($this->request->param('id'));
        $roadtype = ORM::factory('RoadType')->where('level', '=', $road->level + 1)->find();
        $itemtype = ORM::factory('ItemType', $roadtype->itemtype_id);

        $road_distance = $road->get_distance();

        $spec = ORM::factory('Spec')
            ->where('itemtype_id', '=', $roadtype->itemtype_id)
            ->find();
        $spec->time = ceil($spec->time * $road_distance);
        $raws = $roadtype->get_raws($road_distance);
        
        if (HTTP_Request::POST == $this->request->method()) {
            
            $project_type = ORM::factory('ProjectType', $itemtype->projecttype_id);
            
            $project = Model_Project::factory($project_type->key);

            $data = array(
                'name'          => 'Ulepszanie drogi',
                'owner_id'      => $this->character->id,
                'time'          => $spec->time,
                'type_id'       => $project_type->key,
                'location_id'   => $this->location->id,
                'itemtype_id'   => $spec->itemtype_id,
                'created_at'    => $this->game->getRawTime(),
                'road_id'       => $road->id,
            );
            
            $project->values($data);
            $project->save();

            $this->location->add_project($project->id);
            
            foreach ($raws as $raw) {
                
                $project_raw = new Model_Project_Raw();
                $project_raw->project_id = $project->id;
                $project_raw->resource_id = $raw->resource_id;
                $project_raw->amount = 0;
                $project_raw->needed = $raw->amount;
                
                $project_raw->save();
                
            }
            
            $this->redirect('events');
            
        }
        
        $this->template->content = View::factory('user/road/upgrade')
            ->bind('roadtype', $roadtype)
            ->bind('spec', $spec)
            ->bind('raws', $raws);
        
    }
    
}

?>
