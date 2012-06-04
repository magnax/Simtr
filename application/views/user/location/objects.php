<div class="title_bar">Location name - [location type]: obiekty</div>
<?php foreach ($raws as $r): ?>
<?php echo html::anchor('user/event/get_raw/'.$r['id'], '[podnieś]'); ?> <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
<?php endforeach; ?>