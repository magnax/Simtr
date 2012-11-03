<div class="title_bar">
    <?php echo html::anchor('lname?id='.$character['location_id'], $character['location']); ?>: WYJŚCIA
</div>

<?php if ($doors): ?>
    <div>
        <?php echo html::anchor('user/location/enter/'.$doors['id'], '[wyjdź]');?> Wyjście do <?php echo html::anchor('lname?id='.$doors['id'], $doors['name']); ?>
    </div>
<?php endif; ?>

<?php if ($exits): ?>
<?php foreach ($exits as $exit): ?>
    <div>
        <?php echo html::anchor('user/point/e/'.$exit['id'], '[wskaż]'); ?> 
        [idź] [buduj] 
        <?php echo $exit['level'].' do '.html::anchor('user/location/nameform/'.$exit['lid'], $exit['name']).' (kierunek: '.$exit['direction'].')'; ?>
    </div>
<?php endforeach; ?>
<?php else: ?>
    No roads from this location! You're trapped!
<?php endif; ?>
    <?php if ($locationtype == 1): ?>
        <div class="title_bar">
            <?php echo html::anchor('lname?id='.$character['location_id'], $character['location']); ?>: OPIS
        </div>
        <?php echo $location['used_slots']; ?> out of <?php echo $location['res_slots']; ?> resource slots are used<br />
        <?php foreach ($location['resources'] as $res): ?>
            <?php echo html::anchor('user/project/get_raw/'.$res['id'], '[zbieraj]'); ?>
            <?php echo $res['name']; ?> (<?php echo $res['id']; ?>)<br />
        <?php endforeach; ?>
<?php endif; ?>