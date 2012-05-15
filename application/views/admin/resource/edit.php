Surowiec ID: <?php echo $resource['id']; ?>
<?php echo View::factory('admin/resource/_form', array('resource' => $resource))->render(); ?>
