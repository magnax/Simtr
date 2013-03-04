<?php defined('SYSPATH') or die('No direct script access.');

class Model_Resource extends ORM {

    protected $_has_one = array(
        'projecttype' => array(
            'model' => 'ProjectType',
            'foreign_key' => 'id',
            'far_key' => 'projecttype_id',
        ),
    );
    
    public function getGatherBase() {
        return $this->gather_base;
    }

    public function getType() {
        return $this->type;
    }
    
    public function getName() {
        return $this->name;
    }

    public function isRaw() {
        return $this->is_raw;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    
    public function setGatherBase($base) {
        $this->gather_base = $base;
    }
    
    public function toArray() {
        return array(
            'id'=>$this->id,
            'name'=>$this->name,
            'type'=>$this->type,
            'gather_base'=>$this->gather_base,
            'is_raw'=>$this->is_raw
        );
    }
    
//    public function update($post) {
//        $this->name = $post['name'];
//        $this->type = $post['type'];
//        $this->gather_base = $post['gather_base'];
//        $this->is_raw = isset($post['is_raw']) && $post['is_raw'];
//    }

//    abstract public function findOneById($id);
//    abstract public function findAll($name_only = true, $raw = false);
//    abstract public function getDictionaryName($type);
//    abstract public function save();

}

?>
