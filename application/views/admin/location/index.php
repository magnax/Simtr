Lokacje (liczba: <?php echo $count; ?>)
<br />
<?php foreach ($locations as $location): ?>
    <?php echo html::anchor('admin/location/edit/'.$location['id'], $location['name'].' ('.$location['id'].')').'<br />'; ?>
<?php endforeach; ?>

<?php echo html::anchor('admin/location/add/', 'Dodaj lokację').'<br />'; ?>
