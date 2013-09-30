<div class="title_bar">
    <?php echo $character['location']['str']; ?>: WYJŚCIA
</div>

<?php if ($doors): ?>
    <div>
        <?php echo HTML::anchor('user/location/enter/'.$doors['id'], '[wyjdź]');?> Wyjście do <?php echo HTML::anchor('lname/'.$doors['id'], $doors['name']); ?>
    </div>
<?php endif; ?>

<?php if ($location->show_roads()): ?>
    <?php if ($exits): ?>
    <?php foreach ($exits as $exit): ?>
        <div>
            <?php echo HTML::anchor('user/point/e/'.$exit['id'], '[wskaż]'); ?> 
            <?php echo HTML::anchor('user/go/'.$exit['id'], '[idź]'); ?>
            <?php if ($exit['can_be_upgraded']): ?>
                <?php echo HTML::anchor('user/road/upgrade/'.$exit['id'], '[buduj]'); ?>
            <?php endif; ?>
            <?php echo $exit['level'].' do '.HTML::anchor('lname/'.$exit['destination_id'], $exit['destination_name']).' (kierunek: '.$exit['direction'].')'; ?>
        </div>
    <?php endforeach; ?>
    <?php else: ?>
        Nie widać żadnych dróg
    <?php endif; ?>
<?php endif; ?>
        
<?php if ($locationtype == Model_Location::TYPE_TOWN): ?>
    <div class="title_bar">
        <?php echo $character['location']['str']; ?>: Dostępne surowce
    </div>
    Zajętych miejsc wydobycia: <?php echo $used_slots; ?> z <?php echo $res_slots; ?> dostępnych<br />
    <?php foreach ($resources as $res): ?>
        <?php echo HTML::anchor('user/project/get_raw/'.$res->id, '[zbieraj]'); ?>
        <?php echo $res->name; ?> (<?php echo $res->id; ?>)<br />
    <?php endforeach; ?>
<?php endif; ?>