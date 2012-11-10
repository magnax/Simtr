<div class="title_bar">Ludzie</div>
<?php foreach ($characters as $ch): ?>
    <?php echo html::anchor('user/point/person/'.$ch['id'], '[wskaż]'); ?> 
    <?php echo html::anchor('user/people/hit/'.$ch['id'], '[atakuj]'); ?> 
    <?php echo html::anchor('chname?id='.$ch['id'], '[info]'); ?> 
    <?php echo $ch['name']; ?><br />
<?php endforeach; ?>