<?php echo $inventory_menu; ?>
<?php echo View::factory('user/inventory/_items', array('items' => $items))->render(); ?>
