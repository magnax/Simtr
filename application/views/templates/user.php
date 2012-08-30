<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
        <script src="<?= $server_uri;?>/socket.io/socket.io.js"></script>
        <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
        <script src="/assets/js/general.js"></script>
        <script>

        var socket = io.connect('<?= $server_uri;?>');
        var user_id = <?= $user->id; ?>;
        
        socket.on('connect', function(data) {
            
            socket.on('auth', function(incoming) {
                console.log('received auth request');
                socket.emit('user check in', {'user_id': user_id});
                $('#time').removeClass('error');
            });
            
            socket.on('time', function (data) {
                $('#time').html(decodeRawTime(data.time));
            });
            
            socket.on('user_events', function (data) {
                console.log('received user event: '+data);
                var char_id = data.char_id;
                $('#character-'+char_id+' .character_events').html(data['new'] + ' new');
            });
            
            socket.on('disconnect', function () {
                console.log('disconnected!');
                $('#time').addClass('error');
            });
            
        });


        </script>
    </head>
    <body>
        <div id="main">
            <div><?php echo html::anchor('/','Simtr 2'); ?></div>
            <div id="statistics">
                <?php include Kohana::find_file('views', 'common/stats') ?>
            </div>
            <div id="usermenu">
                <?php include Kohana::find_file('views', 'user/menu') ?>
            </div>

            <?php if (isset($character)): ?>
                <?php echo View::factory('user/charinfo', array('character'=>$character)); ?>
            <?php else: ?>
                <?php echo View::factory('user/userinfo', array('user'=>$user)); ?>
            <?php endif; ?>
            <?php echo $content; ?>
            <?php if (isset($character)): ?>
                <div id="buildmenu">
                    <?php include Kohana::find_file('views', 'user/buildmenu') ?>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>