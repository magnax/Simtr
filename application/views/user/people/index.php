<div class="title_bar">Ludzie</div>
<?php foreach ($characters as $ch): ?>
    <?php echo html::anchor('u/char/nameform/'.$ch['id'], '[info]'); ?> <?php echo $ch['name']; ?><br />
<?php endforeach; ?>