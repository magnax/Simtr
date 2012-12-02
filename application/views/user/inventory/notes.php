<?php echo $inventory_menu; ?>
<?php echo View::factory('user/inventory/_notes', array('notes' => $notes))->render(); ?>
