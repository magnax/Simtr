<?php echo View::factory('user/inventory/index')->render(); ?>
Przedmioty
<p>
<?php foreach ($items as $item): ?>
    <?php echo html::anchor('user/inventory/put/'.$item['id'], '[odłóż]'); ?><?php echo $item['name']; ?><br />
<?php endforeach; ?>