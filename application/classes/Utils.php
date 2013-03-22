<?php defined('SYSPATH') or die('No direct script access.');

class Utils {
    
    public static function calculateDistance($x1, $y1, $x2, $y2) {
        return sqrt(pow(abs($x2-$x1), 2)+pow(abs($y2-$y1), 2));
    }
    
    public static function calculateDirection($x1, $y1, $x2, $y2) {
        $vector = array(
            'x' => $x2 - $x1,
            'y' => $y2 - $y1
        );
        
        $d = self::calculateDistance($x1, $y1, $x2, $y2);
        
        if ($vector['x'] == 0 && $vector['y'] == 0) {
            //not vector! just point
            return null;
        } elseif ($vector['x'] == 0) {
            if ($vector['y'] > 0) {
                return 0;
            } else {
                return 180;
            }
        } elseif ($vector['y'] == 0) {
            if ($vector['x'] > 0) {
                return 90;
            } else {
                return 270;
            }
        } else {
            $alpha = rad2deg(asin(abs($vector['x']) / $d));
            if ($vector['x'] > 0 && $vector['y'] > 0) {
                return $alpha;
            } elseif ($vector['x'] > 0 && $vector['y'] < 0) {
                return 180 - $alpha;
            } elseif ($vector['x'] < 0 && $vector['y'] < 0) {
                return 180 + $alpha;
            } else {
                return 360 - $alpha;
            }
        }
    }
    
    public static function reverseDirection($dir) {
        $reverseDir = $dir + 180;
        if ($reverseDir >= 360) {
            $reverseDir -= 360;
        }
        return $reverseDir;
    }
    
    public static function getDirectionString($dir) {
        
        if ($dir <= 5 && $dir >= 355) {
            return 'N';
        } elseif ($dir < 40 && $dir > 5) {
            return 'NNE';
        } elseif ($dir <= 50 && $dir >= 40) {
            return 'NE';
        } elseif ($dir < 85 && $dir > 50) {
            return 'ENE';
        } elseif ($dir <= 95 && $dir >= 85) {
            return 'E';
        } elseif ($dir < 130 && $dir > 95) {
            return 'ESE';
        } elseif ($dir <= 140 && $dir >= 130) {
            return 'SE';
        } elseif ($dir < 175 && $dir > 140) {
            return 'SSE';
        } elseif ($dir <= 185 && $dir >= 175) {
            return 'S';
        } elseif ($dir < 220 && $dir > 185) {
            return 'SSW';
        } elseif ($dir <= 230 && $dir >= 220) {
            return 'SW';
        } elseif ($dir < 265 && $dir > 230) {
            return 'WSW';
        } elseif ($dir <= 275 && $dir >= 265) {
            return 'W';
        } elseif ($dir < 310 && $dir > 275) {
            return 'WNW';
        } elseif ($dir <= 320 && $dir >= 310) {
            return 'NW';
        } else {
            return 'NNW';
        }
        
    }
    
    public static function getLocationName($lname) {
        
        return ($lname) ? $lname : 'unknown location';
        
    }
    
    public static function conditionBar($amount, $full = 1000) {
        echo $amount.'/'.$full;
        $width_health = floor(200*($amount/$full));
        $width_rest = 200-$width_health;
        echo ' <div style="border:1px solid #000;display:inline-block;"><div style="width:'.$width_health.'px;height:10px;background-color:#0c0;display:inline-block;"></div><div style="width:'.$width_rest.'px;height:10px;border:1px 1px 0 1px solid #000;background-color:#c00;display:inline-block;"></div></div>';
        
    }
}

?>
