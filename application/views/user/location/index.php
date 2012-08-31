<div class="title_bar">
    <?php echo $location['name']; ?>: WYJŚCIA
</div>
<?php if ($exits): ?>
<?php foreach ($exits as $exit): ?>
    <div>
        <?php echo html::anchor('user/point/e/'.$exit['id'], '[wskaż]'); ?> 
        [idź] [buduj] 
        <?php echo $exit['level'].' do '.html::anchor('user/location/nameform/'.$exit['lid'], $exit['name']).' (kierunek: '.$exit['direction'].')'; ?>
    </div>
<?php endforeach; ?>
<?php else: ?>
    No exits from this location! You're trapped!
<?php endif; ?>
<div class="title_bar">
    <?php echo $location['name']; ?>: OPIS
</div>
<?php echo $location['used_slots']; ?> out of <?php echo $location['res_slots']; ?> resource slots are used<br />
<?php foreach ($location['resources'] as $res): ?>
    <?php echo html::anchor('user/project/get_raw/'.$res['id'], '[zbieraj]'); ?>
    <?php echo $res['name']; ?> (<?php echo $res['id']; ?>)<br />
<?php endforeach; ?>