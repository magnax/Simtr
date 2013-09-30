<html>
    <head>
        <?= $header; ?>
        <script src="<?= $server_uri;?>/socket.io/socket.io.js"></script>
        <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
        <script src="/assets/js/general.js"></script>
        <script>
            <?php echo View::factory('js/events/user')->bind('chars', $chars)->bind('user', $user); ?>
        </script>
        <script src="<?php echo URL::base(); ?>assets/js/events/user.js"></script>
    </head>
    <body>
        <div id="main">
            <?= $game_info_header; ?>
            <?php echo View::factory('user/userinfo', array('user'=>$user)); ?>
            <?php echo $content; ?>

        </div>
    </body>
</html>