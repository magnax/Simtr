<?php defined('SYSPATH') or die('No direct script access.');

class Model_LocationFactory {
    
    private static $valid_classes = array('twn', 'bld', 'veh');

    static function getInstance($datasource, $data = null) {
        
        $source = get_class($datasource);
        $class = 'Model_Location';
        
        if ($data && isset($data['class']) && in_array($data['class'], self::$valid_classes)) {
            switch ($data['class']) {
                case 'twn':
                    $class .= '_'.$source.'_Town';
                    break;
                case 'bld':
                    $class .= '_'.$source.'_Building';
                    break;
                case 'veh':
                    $class .= '_'.$source.'_Vehicle';
                    break;
                default:
                    $class .= '_'.$source.'_Generic';
                    break;
            }
        } else {
            $class .= '_'.$source.'_Generic';
        }
        
        $returned = new $class($data);
        $returned->setSource($datasource);
        return $returned;
        
    }

}

?>
