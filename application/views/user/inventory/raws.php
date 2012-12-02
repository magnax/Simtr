<?php echo $inventory_menu; ?>
<?php echo View::factory('user/inventory/_raws', array('raws' => $raws))->render(); ?>