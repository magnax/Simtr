<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
        <script src="<?= $server_uri;?>/socket.io/socket.io.js"></script>
        <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
        <script src="/assets/js/general.js"></script>
        <script>
            
        var socket = io.connect('<?= $server_uri;?>');
        var char_id = <?= $character['id']; ?>;
        var project_id = <?= $character['project_id']; ?>;
        
        //project update
        <? if ($character['project_id']): ?>
            var project = {
                'time_elapsed': <?= $character['myproject']['time_elapsed']; ?>,
                'time_zero': <?= $character['myproject']['time_zero']; ?>,
                'time': <?= $character['myproject']['time']; ?>,
                'speed': <?= $character['myproject']['speed']; ?>
            };
        <? else: ?>
            var project = {};
        <? endif; ?>

        
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
            var exists = $('#events ul li[data-id="' + data.event_id + '"]').length;
            console.log(exists);
            if (exists == 0) {
                console.log('adding...');
                $('#events ul').prepend('<li data-id="' + data.event_id + '" class="new_event">' + data.text.date + ': ' + data.text.text + '</li>');
            }
          });

        socket.on('time', function (data) {
            $('#time').html(decodeRawTime(data.time));
            if (project.hasOwnProperty('time_zero')) {
                var now = (project.time_elapsed + ((data.time - project.time_zero) * project.speed)) / project.time * 100;                  
                if (now >= 100) {
                    $('#project_percent').html('<b>100</b>');
                    project = {};
                } else {
                    $('#project_percent').html(Math.round(now*100)/100);
                }
            }
        });
        
        //count connected users
        socket.on('usercount', function(data) {
            //console.log('connected users: '+data.usercount);
            $('#count_active_users').html(data.usercount); 
        });
        
        //count connected chars
        socket.on('charcount', function(data) {
            console.log('connected chars: '+data.charcount);
            $('#count_active_chars').html(data.charcount); 
        });
        
        socket.on('disconnect', function () {
            console.log('disconnected!');
            $('#time').addClass('error');
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
                })
                .error(function() {
                    $('#talk').removeAttr("disabled");
                    $('#event_input_small').removeAttr("disabled");
                    $('#talk_error').html('something went wrong').delay(6000).hide(1000);
                });
            });
            $('#insert').live('click', function() {
                event.preventDefault();
                var exists = $('#events ul li[data-id="9888"]').length;
                console.log(exists);
            });
            
            var timer = function() {
                    var now = (project.time_elapsed + ((Math.floor(Date.now()/1000) - project.time_zero) * project.speed)) / project.time * 100;
//                    
                    $('#project_percent').html(Math.round(now*100)/100);
                    //console.log(now);
                    //window.setTimeout(timer, 1000);
                };
//            //project update, if project
//            //if (project) {
                //window.setTimeout(timer, 1000); 
//            //}
            
        });
        
        </script>
    </head>
    <body>
        <div id="main">
            <div><?php echo html::anchor('/','Fabular (pre-alpha)'); ?> (U:<span id="count_active_users">0</span>, C:<span id="count_active_chars">0</span>)</div>
            <?php if (isset($error) && $error): ?>
                <div class="error">Błąd: <?php echo $error; ?></div>
            <?php endif; ?>
            <?php if (isset($message) && $message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            <div id="statistics">
                <?php include Kohana::find_file('views', 'common/stats') ?>
            </div>
            <div id="usermenu">
                <?php include Kohana::find_file('views', 'user/menu') ?>
            </div>

            <?php echo View::factory('user/charinfo', array('character'=>$character)); ?>
            
            <?php echo $content; ?>
            
            <div class="title_bar">Menu postaci</div>
            <div id="buildmenu">
                <?php include Kohana::find_file('views', 'user/buildmenu') ?>
            </div>
            
        </div>
    </body>
</html>