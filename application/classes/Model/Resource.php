<?php defined('SYSPATH') or die('No direct script access.');

class Model_Resource extends ORM {

    protected $_belongs_to = array(
        'projecttype' => array(
            'model' => 'ProjectType',
            'foreign_key' => 'projecttype_id',
            'far_key' => 'id',
        ),
    );

}

?>
