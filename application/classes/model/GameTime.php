<?php

class Model_GameTime {

    /**
     * ścieżka do demona czasu
     */
    const PATH = '/home/mn/simtrd/d.py';

    /**
     * długość dnia w sekundach
     */
    const DAY_LENGTH = 86400; // => 24 * 60 * 60

    protected $year;
    protected $day;
    protected $h;
    protected $m;
    protected $s;
    private $raw_time;

    public function  __construct() {

        $this->raw_time = self::getRawTime();
        $t = $this->decodeRawTime($this->raw_time);

        $this->year = $t['y'];
        $this->day = $t['d'];
        $this->day_of_year = $t['f'];
        $this->h = $t['h'];
        $this->m = $t['m'];
        $this->s = $t['s'];

    }

    public static function getRawTime() {
        $output = shell_exec(self::PATH.' say');
        if (!$output) {
            $output = time() - 1292300000;
        } elseif (strpos($output, 'rror:')) {
            throw new Exception('Not running!');
        }

        return str_replace("\n", '', $output);
    }

    public function getTime() {
        return $this->h.':'.$this->m.':'.$this->s;
    }

    public function getDate() {
        return $this->year.'-'.$this->day;
    }

    public function getDateTime() {
        return $this->date.'-'.$this->time;
    }

    public static function decodeRawTime($raw_time) {
        /*
        $day_length = 60*60*24;
        $d = floor($raw_time / ($day_length));
        $y = floor($d / 20);
        $r1 = $raw_time - ($y * 20 * $day_length);
        $f = floor($r1 / ($day_length));
        $rest = $raw_time - ($d * $day_length);
        $h = floor($rest / 3600);
        $rest = $rest - ($h * 3600);
        $m = floor($rest / 60);
        $s = $rest - ($m*60);
         *
         */
        $s = $raw_time % 60;
        $r = ($raw_time - $s) / 60;
        $m = $r % 60;
        $r = ($r - $m) / 60;
        $h = $r % 24;
        $r = ($r - $h) / 24;
        $d = $r % 24;
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
