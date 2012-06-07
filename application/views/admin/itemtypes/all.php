Item types:<p>

<?php foreach ($itemtypes as $itemtype): ?>
    <?php echo html::anchor('admin/itemtypes/edit/'.$itemtype['id'], $itemtype['id']); ?> <?php echo $itemtype['name']; ?><br />
<?php endforeach; ?>
<p><?php echo html::anchor('admin/itemtypes/add', 'Add item type'); ?><br />
