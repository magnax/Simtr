var projects = {};
        
<?php if (isset($chars) && count($chars)): ?>
    <?php foreach ($chars as $char): ?>
        <?php if ($char['myproject']): ?>
            projects[<?php echo $char['id']; ?>] = {
                'time_elapsed': <?php echo $char['myproject']['time_elapsed']; ?>,
                'time_zero': <?php echo $char['myproject']['time_zero']; ?>,
                'time': <?php echo $char['myproject']['time']; ?>,
                'speed': <?php echo $char['myproject']['speed']; ?>
            };
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>

var socket = io.connect('<?php echo $server_uri;?>');
var user_id = <?php echo $user->id; ?>;