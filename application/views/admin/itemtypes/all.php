Item types:<p>

<?php foreach ($itemtypes as $itemtype): ?>
    <?php echo HTML::anchor('admin/itemtypes/edit/'.$itemtype['id'], $itemtype['id']); ?> <?php echo $itemtype['name']; ?><br />
<?php endforeach; ?>
<p><?php echo HTML::anchor('admin/itemtypes/add', 'Add item type'); ?><br />
