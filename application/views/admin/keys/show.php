<h2>Key: <?php echo $key; ?></h2>
<h3>Type: <?php echo $type; ?></h3>
<?php echo $value; ?>
<br /><br />
<a href="<?php echo URL::base(); ?>admin/keys/delete/<?php echo $key; ?>">
    usuń
</a>