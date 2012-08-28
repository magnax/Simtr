<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
        <script src="<?= $server_uri;?>/socket.io/socket.io.js"></script>
        <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
        <script src="/assets/js/general.js"></script>
        <script>
            
        var socket = io.connect('<?= $server_uri;?>');
        var char_id = <?= $character['id']; ?>;
        
        socket.on('connect', function(data) {
            console.log('connected');
            socket.on('auth', function(incoming) {
                console.log('received auth request');
                socket.emit('check in', {'char_id': char_id})
                $('#time').removeClass('error');
            });
        });
        
        socket.on('events', function (data) {
            console.log('got data');
            var exists = $('#events ul li[data-id="' + data.event_id + '"]').html();
            console.log(exists);
            if (exists == undefined) {
                $('#events ul').prepend('<li data-id="' + data.event_id + '">' + data.text.date + ': ' + data.text.text + '</li>');
            }
          });

        socket.on('time', function (data) {
            $('#time').html(decodeRawTime(data.time));
        });

        $(document).ready(function() {
            console.log('started app');
            $('#talk_all').submit(function(event) {
                event.preventDefault(); 
                $('#event_input_small').attr("disabled", "disabled");
                $('#talk').attr("disabled", "disabled");
                var text = $('#event_input_small').val();
                $.post('/events/talkall', {'text':text}, function(data) {
                    $('#event_input_small').val('');
                    $('#talk').removeAttr("disabled");
                    $('#event_input_small').removeAttr("disabled");
                });
            });
            $('#insert').live('click', function() {
                event.preventDefault(); 
                $('#events ul').prepend('<li>new item appended!</li>');
                console.log('insert');
            });
        });
        
        </script>
    </head>
    <body>
        <div id="main">
            <?php if (isset($err) && $err): ?>
                <div class="error">Błąd: <?php echo $err; ?></div>
            <?php endif; ?>
            <?php if (isset($msg) && $msg): ?>
                <div class="message"><?php echo $msg; ?></div>
            <?php endif; ?>
            <div><?php echo html::anchor('/','Simtr 2'); ?></div>
            <div id="statistics">
                <?php include Kohana::find_file('views', 'common/stats') ?>
            </div>
            <div id="usermenu">
                <?php include Kohana::find_file('views', 'user/menu') ?>
            </div>

            <?php echo View::factory('user/charinfo', array('character'=>$character)); ?>
            
            <?php echo $content; ?>
            
            <div id="buildmenu">
                <?php include Kohana::find_file('views', 'user/buildmenu') ?>
            </div>
            
        </div>
    </body>
</html>