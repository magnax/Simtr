<p>All users:
<p>
    <?php foreach ($users as $user): ?>
        <?php echo HTML::anchor('/admin/characters/edit/'.$user['id'], $user['id']); ?> 
        <?php echo $user['name']; ?> <?php echo $user['sex']; ?> 
        <?php echo HTML::anchor('/admin/characters/del/'.$user['id'], '[usuń]'); ?>
        <br />
    <?php endforeach; ?>
</p>