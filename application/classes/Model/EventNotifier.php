<?php defined('SYSPATH') or die('No direct script access.');

/**
 * class to notify users and characters currently receiving events
 * 
 */

class Model_EventNotifier {
    
    public static function notify(ElephantIOClient $elephant, Model_Character $notified_char, Model_Event $event) {

        if ($notified_char->connectedChar()) {

            $data = json_encode(array(
                'name' => 'push_event',
                'args' => array(
                    'event_id'=> $event->id,
                    'char_id' => $notified_char->id,
                    'text' => $event->format_output($notified_char, $event->id),
                )
            ));               
            echo 'notifying char: '.$notified_char->id;

        } else {

            RedisDB::rpush("new_events:{$notified_char->id}", $event->id);

            if ($notified_char->connectedUser()) {
                //user is watching, add to event query
                $data = json_encode(array(
                    'name' => 'push_user_event',
                    'args' => array(
                        'user_id' => $notified_char->user_id,
                        'char_id' => $notified_char->id,
                        'event_id' => $event->id,
                    )
                ));
                echo 'notifying user: '.$notified_char->user_id;
            }
        }
        
        if (isset($data)) {
            $elephant->send(ElephantIOClient::TYPE_EVENT, null, null, $data);
        }
           
    }
    
}

?>
