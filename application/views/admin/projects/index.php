<?php foreach ($projects as $id=>$project): ?>
<a href="/admin/projects/edit/<?php echo $id; ?>"><?php echo $id; ?></a>: <?php echo $project['type_id']; ?><br />
<?php endforeach; ?>
