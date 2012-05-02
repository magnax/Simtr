<?php

class Model_ProductionSpec_Redis extends Model_ProductionSpec {

    public function findOneById($id) {

        $ps = new Model_ProductionSpec_Redis($this->source, $this->dict);
        $tmp_ps = $this->source->get("productionspec:$id");
        if ($tmp_ps) {
            $tmp_ps = json_decode($tmp_ps, true);
            //"hydration":
            foreach ($tmp_ps as $key => $val) {
                $ps->{$key} = $val;
            }
            //rozbicie surowców:
            if (is_array($ps->raws)) {
                foreach ($ps->raws as &$raw) {
                    $r = explode(':', $raw);
                    $raw = array(
                        'id'=>$r[0],
                        'amount' => $r[1]
                    );
                }
            }
        }
        return $ps;
    }

}

?>
