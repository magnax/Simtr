Menu produkcji:<br />
<?php if ($menu['parent'] !== null): ?>
<div class="build_menu"><?php echo $current; ?></div>
    Powrót: <?php echo html::anchor('u/build/'.$menu['parent'], ($menu['parent']) ? $menu['parent']:'Menu budowania'); ?><br />
<?php endif; ?>
<?php foreach ($menu['items'] as $key=>$item): ?>
    <?php echo html::anchor('u/build/'.$key, $item); ?><br />
<?php endforeach; ?>
<?php foreach ($menu['products'] as $key=>$item): ?>
    <?php echo html::anchor('u/build/form/'.$key, $item); ?><br />
<?php endforeach; ?>