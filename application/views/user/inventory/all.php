<?php echo $inventory_menu; ?>
<?php echo View::factory('user/inventory/_raws', array('raws' => $raws))->render(); ?>
<?php echo View::factory('user/inventory/_items', array('items' => $items))->render(); ?>
<?php echo View::factory('user/inventory/_notes', array('notes' => $notes))->render(); ?>