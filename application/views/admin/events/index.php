<?php foreach ($events as $id=>$event): ?>
<a href="/admin/events/edit/<?php echo $id; ?>"><?php echo $id; ?></a>: <?php echo $event['type']; ?><br />
<?php endforeach; ?>
