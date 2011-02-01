<?php

class Controller_User_Inventory extends Controller_Base_Character {

    public function action_index($type = 'raws') {

        $types = array('raws', 'items', 'notes', 'keys', 'coins');
        if (!in_array($type, $types) && $type != 'all') {
            $type = 'raws';
        }

        if ($type == 'all') {
            foreach ($types as $t) {
                $action = 'action_'.$t;
                $this->$action();
            }
        } else {
            $action = 'action_'.$type;
            $this->$action();
        }

    }

    public function action_raws() {
        $raws = $this->character->getRaws();
        $this->template->content .= View::factory('user/inventory/raws', array('raws'=>$raws));
    }

    public function action_items() {
        $this->template->content .= View::factory('user/inventory/items');
    }

    public function action_notes() {
        $this->template->content .= View::factory('user/inventory/notes');
    }

    public function action_keys() {
        $this->template->content .= View::factory('user/inventory/keys');
    }

    public function action_coins() {
        $this->template->content .= View::factory('user/inventory/coins');
    }
}

?>
