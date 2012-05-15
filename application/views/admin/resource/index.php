<?php foreach ($resources as $res): ?>
    <?php echo html::anchor('admin/resource/edit/'.$res['id'], $res['name']); ?>
    <?php echo $res['gather_base']; ?> 
    <?php if(isset($res['is_raw'])): ?>
        <?php echo $res['is_raw'] ? ', raw!' : ''; ?>
    <?php endif; ?>
    <br />
<?php endforeach; ?>
<?php echo html::anchor('admin/resource/new', 'Dodaj nowy surowiec'); ?>

