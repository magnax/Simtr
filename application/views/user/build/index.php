Menu produkcji:<br />
<?php foreach ($menu as $m): ?>
    <?php echo html::anchor('user/build/'.$m->id, $m->name); ?><br />
    <?php if ($submenu['id'] == $m->id): ?>
        <?php if (count($submenu['items'])): ?>
            <?php foreach ($submenu['items'] as $item): ?>
    -- <?php echo $item->item->name; ?> <?php echo html::anchor('user/project/builditem/'.$item->item->id, '[wytwarzaj]'); ?> <br />
            <?php endforeach; ?>
        <?php else: ?>
                [brak]<br />
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; ?>