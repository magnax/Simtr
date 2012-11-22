<?php foreach ($events as $id=>$event): ?>
<a href="/events/edit/<?php echo $id; ?>"><?php echo $id; ?></a>: <?php echo $event['type']; ?><br />
<?php endforeach; ?>
