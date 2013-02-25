<div class="title_bar">
    <?php echo html::anchor('lname?id='.$character['location_id'], '['.$character['location'].']'); ?> - 
    [<?= $locationtype; ?>]: obiekty
</div>

<?php if ($lockable): ?>
    <div>Drzwi, poziom zamka: <?php echo ($lock->level > 0)? $lock->level : 'brak'; ?>
        <?php if ($lock->level > 0): ?>
            (nr: <?php echo $lock->nr; ?>)
            <?php if ($lock->locked): ?>
                zamknięte
            <?php else: ?>
                otwarte
            <?php endif; ?>
                
            <?php if ($has_key): ?>
                <?php echo ($lock->locked)? HTML::anchor('unlock','[otwórz]'):HTML::anchor('lock', '[zamknij]'); ?>
            <?php endif; ?>
                
            <?php if ($lock->level < $max_lock_level): ?>
                <?php echo HTML::anchor('lock/upgrade','[ulepsz]'); ?>
            <?php endif; ?>
        <?php else: ?>
            <?php echo HTML::anchor('lock/upgrade','[wstaw zamek]'); ?>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php if (count($notes)): ?>
<div class="list">
    <div class="objects-menu">Notatki</div>
    <?php foreach ($notes as $note): ?>
    <div>
        <a href="/user/notes/get/<?=$note['id']; ?>" title="Podnieś notatkę">
            <img src="/assets/images/get.png" height=32 width=32>
        </a>
        <a href="/user/notes/copy/<?=$note['id']; ?>" title="Kopiuj notatkę">
            <img src="/assets/images/copy.png" height=32 width=32>
        </a>
        <a href="/user/notes/view/<?=$note['id']; ?>" title="Czytaj notatkę">
            <img src="/assets/images/view.png" height=32 width=32>
        </a>
        <?php echo $note['title']; ?>
    </div>
    <?php endforeach; ?>
</div>
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