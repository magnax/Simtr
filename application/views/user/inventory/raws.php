<?php echo View::factory('user/inventory/index')->render(); ?>
<div class="list">
    Surowce: <br />
    <?php foreach ($raws as $r): ?>
        <?php echo html::anchor('events/put_raw/'.$r['id'], '[połóż]'); ?>
        <?php echo html::anchor('events/give_raw/'.$r['id'], '[podaj]'); ?>
        <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
    <?php endforeach; ?>
</div>
