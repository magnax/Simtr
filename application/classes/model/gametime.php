<?php

class Model_GameTime {

    /**
     * path to game time deamon
     */
    const PATH = '/home/local/lib/simtr/d.py';
    //on server: 
    //const PATH = 'python /home/magnax/domains/magnax.pl/public_html/game/simtrd/d.py';

    /**
     * day length in seconds
     */
    const DAY_LENGTH = 86400; // => 24 * 60 * 60

    protected $year;
    protected $day;
    protected $day_of_year;
    protected $h;
    protected $m;
    protected $s;
    
    //current time
    public $raw_time;
    
    //path to daemon
    private $path;

    public function  __construct($daemon_path = null) {

        $this->path = $daemon_path ? $daemon_path : self::PATH;

        $this->raw_time = $this->getRawTime($this->path);
        $t = $this->decodeRawTime($this->raw_time);

        $this->year = $t['y'];
        $this->day = $t['d'];
        $this->day_of_year = $t['f'];
        $this->h = $t['h'];
        $this->m = $t['m'];
        $this->s = $t['s'];

    }

    public function getRawTime($daemon_path = null) {
        
        if (!$daemon_path) {
            $daemon_path = $this->path ? $this->path : self::PATH;
        }
        
        $output = shell_exec($daemon_path.' say');
        
        if (strpos($output, 'Error:') !== false) {
            throw new DaemonNotRunningException('Not running!');
        }
        
        $output = intval(str_replace("\n", '', $output));
        
        if (is_integer($output) && $output) {
            return $output;
        } else {
            throw new BadDaemonException('Bad daemon? Should be installed in '.$daemon_path);
        }
        
    }

    public function getTime() {
        return sprintf("%02d:%02d:%02d", $this->h, $this->m, $this->s);
    }

    public function getDate() {
        return $this->year.'/'.$this->day.' ('.$this->day_of_year.')';
    }

    public function getDateTime() {
        return $this->getTime().' '.$this->getTime();
    }

    public static function decodeRawTime($raw_time) {

        $s = $raw_time % 60;
        $r = ($raw_time - $s) / 60;
        $m = $r % 60;
        $r = ($r - $m) / 60;
        $h = $r % 24;
        $d = ($r - $h) / 24;
        $y = floor($d / 20);
        $f = $d % 20;
        return array(
            'y'=>$y,
            'f'=>$f,
            'd'=>$d,
            'h'=>$h,
            'm'=>$m,
            's'=>$s
        );
    }

    public static function formatDateTime($raw_time, $format = 'd-h.m') {
        $d = self::decodeRawTime($raw_time);
        $search = array('y', 'f', 'd', 'h', 'm', 's');
        $replace = array($d['y'], $d['f'], $d['d'], $d['h'], $d['m'], $d['s']);
        return str_replace($search, $replace, $format);
    }

}

?>
