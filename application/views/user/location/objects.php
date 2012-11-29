<div class="title_bar"><?php echo html::anchor('lname?id='.$character['location_id'], '['.$character['location'].']'); ?> - [<?= $locationtype; ?>]: obiekty</div>

<?php if (count($notes)): ?>
    <div class="objects-menu">Notatki</div>
    <?php foreach ($notes as $note): ?>
        <?php echo html::anchor('user/notes/get/'.$note['id'], '[podnieś]'); ?>
        <?= html::anchor('user/notes/view/'.$note['id'], '[czytaj]'); ?>
        <?php echo $note['title']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>
        
<?php if (count($raws)): ?>
    <div class="objects-menu">Surowce</div>
    <?php foreach ($raws as $r): ?>
    <?php echo html::anchor('events/get_raw/'.$r['id'], '[podnieś]'); ?> <?php echo $r['amount']; ?> gram <?php echo $r['name']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($items)): ?>
    <div class="objects-menu">Przedmioty</div>
    <?php foreach ($items as $item): ?>
        <?php echo html::anchor('user/location/getitem/'.$item['id'], '[podnieś]'); ?>
        <?php echo $item['name']; ?><br />
    <?php endforeach; ?>
<?php endif; ?>

<?php if (count($corpses)): ?>
    <div class="objects-menu">Ciała</div>
    <?php foreach ($corpses as $corpse): ?>
        <?php echo html::anchor('user/location/bury/'.$corpse['id'], '[zakop]'); ?> Ciało <?php echo html::anchor('chname?id='.$corpse['character_id'], $corpse['name']); ?><br />
    <?php endforeach; ?>
<?php endif; ?>