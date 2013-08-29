<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User_Go extends Controller_Base_Character {
    
    public function action_index() {
        
        //potrzebne jest 10 parametrów:
        // - kierunek (ponieważ droga ma location_1 i location_2, więc
        //   kierunek będzie wskazywał W STRONĘ KTÓREJ LOKACJI się poruszamy (1 || 2)
        //
        // - prędkość (będzie podana jako współczynnik, o który należy pomnożyć prędkość bazową
        // - x (aktualna koordynata X)
        // - y ( -//- Y)
        // - x1;y1 - koordynaty lokacji 1
        // - x2;y2 - koordynaty lokacji 2
        // - czas ostatniej aktualizacji pozycji
        $position = new Model_Position();
        
        $road = new Model_Road($this->request->param('id'));
        
        //wyruszam z lokacji, która w obiekcie drogi może być jako 1 lub 2, cel
        //będzie zatem odwrotny do pozycji obecnej lokacji
        $position->dest = ($road->location_1_id == $this->character->location_id) ? 2 : 1;
        
        // (x1,y1) to zawsze wsp. lokacji z której idziemy, (x2, y2) - docelowej
        $key1 = 'location_'.(2 / $position->dest);
        $key2 = 'location_'.$position->dest;
        
        $position->x1 = $road->$key1->town->x;
        $position->y1 = $road->$key1->town->y;
        $position->x2 = $road->$key2->town->x;
        $position->y2 = $road->$key2->town->y;
        $position->x = $this->character->location->town->x;
        $position->y = $this->character->location->town->y;
        $position->dir = Utils::calculateDirection($position->x1, $position->y1, $position->x2, $position->y2);

        $position->time = $this->raw_time;
        
        $position->speed = Model_Position::BASE_SPEED * 900;
        
        $position->save();
        
        //first get characters to notify
        $recipients = $this->character->location->getVisibleCharacters();
        $from_location_id = $this->character->location->id;
        $to_location_key = $key2 . '_id';
                
        //set_position sets Position Object to character and changes location to
        //road location
        $this->character->set_position($position, $road);
        
        //wysłanie eventu
        $event = new Model_Event();
        $event->type = Model_Event::DEPARTURE_INFO;
        $event->date = $this->game->getRawTime();

        $event->add('params', array('name' => 'sndr', 'value' => $this->character->id));
        $event->add('params', array('name' => 'location_id', 'value' => $road->$to_location_key));
        $event->add('params', array('name' => 'from_location_id', 'value' => $from_location_id));

        $event->save();

        $event->notify($recipients);
        
        $this->redirect('events');
        
    }
    
    public function action_back() {
        
        $this->character->back();
        
        //utworzyć i wysłać event
        
        $this->redirect('events');
        
        //$this->template->content = 'Wracasz drogą '.$this->request->param('id');
        
    }
    
}

?>
