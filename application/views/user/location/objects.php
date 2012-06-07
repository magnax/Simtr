<div class="title_bar">Location name - [location type]: obiekty</div>
<b>Surowce</b><p>
<?php foreach ($raws as $r): ?>
<?php echo html::anchor('user/event/get_raw/'.$r['id'], '[podnieś]'); ?> <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
<?php endforeach; ?>
<b>Przedmioty</b>
<p>
<?php foreach ($items as $item): ?>
    <?php echo html::anchor('user/location/getitem/'.$item['id'], '[podnieś]'); ?><?php echo $item['name']; ?><br />
<?php endforeach; ?>