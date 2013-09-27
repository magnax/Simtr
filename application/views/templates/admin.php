<head>
    <link rel="stylesheet" href="<?php echo URL::base(); ?>assets/css/admin.css">
</head>
<body>
    <?php echo View::factory('common/admin_menu'); ?>
    <?php echo $content; ?>
</body>
