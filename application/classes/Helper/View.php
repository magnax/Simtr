<?php

class Helper_View {
    
    public static function LocationName(Model_Building $building, array $character) {
        return HTML::anchor('lname/'.$building->location->id, $building->location->get_lname($character['id']));
    }
    
    public static function CharacterName($id, $name) {
        return sprintf(str_replace('{{base}}', URL::base(), Model_Character::$chname_link), $id, $name);
    }
    
    public static function LocationInfo(Model_Character $character, $links = true) {
        
        $location = new Model_Location($character->location_id);
        
        $str = '';

        if ($location->parent_id) {
            
            $parent_location = ORM::factory('Location', $location->parent_id);
            $parent_name = $parent_location->get_lname($character->id);
            $str = ($links) ? HTML::anchor('lname/'.$parent_location->id, $parent_name) : $parent_name;
            $str .= ' : ';
            $name = $location->get_lname($character->id);
            $str .= ($links) ? HTML::anchor('lname/'.$location->id, $name) : $name;
            
        } elseif ($location->locationtype_id == 3) { // == Road location
            
            $location_array = array();
            
            $road = new Model_Road(array('location_id' => $location->id));
            $position = $character->get_position_object();
            $location_array['time_zero'] = $position->time;
            
            $progress = $position->get_progress();
            $show_progres = round(100*$position->get_progress(), 2);
            $location_array['current_progress'] = $progress * 100;
            
            $dist = $position->get_distance();
            $location_array['progress_for_second'] = 100 * $position->speed / $dist;
            
            //"TO" location
            $dest_location_id = $road->{"location_{$position->dest}_id"};
            $dest_location = new Model_Location($dest_location_id);
            $dest_name = $dest_location->get_lname($character->id);
            if ($links) {
                $dest_name = HTML::anchor('lname/'.$dest_location->id, $dest_name);
            }
            
            //"FROM" location
            $from = 2 / $position->dest;
            $from_location_id = $road->{"location_{$from}_id"};
            $from_location = new Model_Location($from_location_id);
            $from_name = $from_location->get_lname($character->id);
            if ($links) {
                $from_name = HTML::anchor('lname/'.$from_location->id, $from_name);
            }

            $percent_for_tick = $location_array['progress_for_second'] * 5;    
            
            //$str = "podróżuje z: $from_name do: <span id=\"dest_location\">$dest_name>/span> ($dist px @ {$position->speed} px/sek. = $percent_for_tick procent na tik) (<span id=\"travel_progress\">$show_progres</span>%)";
            $str = "podróżuje z: $from_name do: <span id=\"dest_location\">$dest_name</span> (<span id=\"travel_progress\">$show_progres</span>%)";
            
            if ($links) {
                $str .= ' ' . HTML::anchor('user/go/back', '[zawróć]');
            }
            
            /**
             * some examples of progress calculation
             */
//            $position->move($position->time + 5);
//            echo "New position: {$position->x}; {$position->y}<br>";
//            
//            $position->x = 88;
//            $position->x1 = 88;
//            $position->y = -8;
//            $position->y1 = -8;
//            $position->dir = Utils::calculateDirection($position->x1, $position->y1, $position->x2, $position->y2);
//            $position->move($position->time + 1);
//            echo "New position: {$position->x}; {$position->y}<br>";
//            
//            $position->x = -88;
//            $position->x1 = -88;
//            $position->y = -8;
//            $position->y1 = -8;
//            $position->dir = Utils::calculateDirection($position->x1, $position->y1, $position->x2, $position->y2);
//            $position->move($position->time + 1);
//            echo "New position: {$position->x}; {$position->y}<br>";
//            
//            $position->x = -88;
//            $position->x1 = -88;
//            $position->y = 8;
//            $position->y1 = 8;
//            $position->dir = Utils::calculateDirection($position->x1, $position->y1, $position->x2, $position->y2);
//            $position->move($position->time + 1);
//            echo "New position: {$position->x}; {$position->y}<br>";
//            
//            $position->x = 0;
//            $position->x1 = 0;
//            $position->y = 0;
//            $position->y1 = 0;
//            $position->x2 = 80;
//            $position->y2 = 0;
//            $position->dir = Utils::calculateDirection($position->x1, $position->y1, $position->x2, $position->y2);
//            $position->move($position->time + 1);
//            echo "New position: {$position->x}; {$position->y}<br>";
            
        } else {
            //is grand location
            $name = $location->get_lname($character->id);
            $str .= ($links) ? HTML::anchor('lname/'.$location->id, $name) : $name;
        }
        
        $location_array['str'] = $str;
        
        return $location_array;
        
    }
    
    public static function FormatSkill($level) {
        
        echo Model_Skill::$level_names[$level];
        
    }
}

?>
