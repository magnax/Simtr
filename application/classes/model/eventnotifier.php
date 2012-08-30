<?php defined('SYSPATH') or die('No direct script access.');

/**
 * class to notify users and characters currently receiving events
 * 
 */

class Model_EventNotifier {
    
    public static function notify($recipients, $event_id, $source, $lang) {

        require_once APPPATH . 'modules/elephant/classes/client.php';
        
        $elephant = new Client(Kohana::$config->load('general.server_ip'));
        $elephant->init();

        $event_dispatcher = Model_EventDispatcher::getInstance($source, $lang);

        foreach ($recipients as $recipient) {

            $notifyChar = new Model_Character($recipient);

            if ($notifyChar->connectedChar($source)) {

                $data = json_encode(array(
                    'name' => 'push_event',
                    'args' => array(
                        'event_id'=> $event_id,
                        'char_id' => $recipient,
                        'text' => $event_dispatcher->formatEvent($event_id, $recipient),
                    )
                ));               

                $elephant->send(Client::TYPE_EVENT, null, null, $data);
                echo 'notifying char: '.$recipient;

            } else {

                $source->rpush("new_events:$recipient", $event_id);

                if ($notifyChar->connectedUser($source)) {
                    //user is watching, add to event query
                    $data = json_encode(array(
                        'name' => 'push_user_event',
                        'args' => array(
                            'user_id' => $notifyChar->user_id,
                            'char_id' => $recipient,
                            'event_id' => $event_id,
                        )
                    ));

                    $elephant->send(Client::TYPE_EVENT, null, null, $data);
                    echo 'notifying user of: '.$recipient;
                }
            }

        }
            
    }
    
}

?>
