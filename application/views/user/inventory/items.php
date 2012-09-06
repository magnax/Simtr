<?php echo View::factory('user/inventory/index')->render(); ?>
Przedmioty
<p>
<?php foreach ($items as $item): ?>
    <?= html::anchor('user/inventory/put/'.$item['id'], '[odłóż]'); ?> <?= $item['state']; ?> <?= $item['name']; ?><br />
<?php endforeach; ?>