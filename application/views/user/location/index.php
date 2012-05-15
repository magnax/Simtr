<div class="title_bar">
    <?php echo $l['name']; ?>: WYJŚCIA
</div>
<?php if ($l['exits']): ?>
<?php foreach ($l['exits'] as $exit): ?>
    <div>
        <?php echo '[ikony] '.$exit['level'].' do '.html::anchor('u/location/nameform/'.$exit['lid'], $exit['name']).' (kierunek: '.$exit['direction'].')'; ?>
    </div>
<?php endforeach; ?>
<?php else: ?>
    No exits from this location! You're trapped!
<?php endif; ?>
<div class="title_bar">
    <?php echo $l['name']; ?>: OPIS
</div>
<?php echo $l['used_slots']; ?> out of <?php echo $l['res_slots']; ?> resource slots are used<br />
<?php foreach ($l['resources'] as $res): ?>
    <?php echo html::anchor('u/project/get_raw/'.$res['id'], '[zbieraj]'); ?>
    <?php echo $res['name']; ?> (<?php echo $res['id']; ?>)<br />
<?php endforeach; ?>