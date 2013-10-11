<html>
    <head>
        <?php echo View::factory('common/header'); ?>
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
                'id': <?= $character['project_id']; ?>,
                'time_elapsed': <?= $character['myproject']['time_elapsed']; ?>,
                'time_zero': <?= $character['myproject']['time_zero']; ?>,
                'time': <?= $character['myproject']['time']; ?>,
                'speed': <?= $character['myproject']['speed']; ?>
            };
        <? else: ?>
            var project = {};
        <? endif; ?>

        //travel update
        <? if (isset($character['location']['time_zero'])): ?>
            var travel = {
                'time_zero': <?= $character['location']['time_zero']; ?>,
                'current_progress': <?= $character['location']['current_progress']; ?>,
                'progress': <?= $character['location']['progress_for_second']; ?>
            };
            travel.getProgress = function(time) {
                return this.current_progress + (this.progress * (time - this.time_zero));
            }
        <? else: ?>
            var travel = {};
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
                if (data.text.type === 'ArriveInfo') {
                    $('#location').html($('#dest_location').html());
                    travel = {};
                }
            }
          });

        socket.on('time', function (data) {
            $('#time').html(decodeRawTime(data.time));
            if (project.hasOwnProperty('time_zero')) {
                var selector = '.project_' + project.id + '_percent';
                var now = (project.time_elapsed + ((data.time - project.time_zero) * project.speed)) / project.time * 100;                  
                if (now >= 100) {
                    $(selector).html('<b>100%</b>');
                    project = {};
                } else {
                    $(selector).html((Math.round(now*100)/100 + '%').replace('.', ','));
                }
            }
            if (travel.hasOwnProperty('time_zero')) {
                var progress = travel.getProgress(data.time);
                if (progress >= 100) {
                    //$('#location').html($('#dest_location').html());
                    $('#travel_progress').html('<b>100</b>');
                    travel = {};
                } else {
                    $('#travel_progress').html(Math.round(progress*100)/100);
                }
            }
        });
        
        //count connected users
        socket.on('usercount', function(data) {
            $('#count_active_users').html(data.usercount); 
        });
        
        //count connected chars
        socket.on('charcount', function(data) {
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
        });
        
        </script>
    </head>
    <body>
        <div id="main">
            <?= $game_info_header; ?>
            <?php echo View::factory('user/charinfo', array('character'=>$character)); ?>
            
            <?php echo $content; ?>
            
            <div class="title_bar">Menu postaci</div>
            <div id="buildmenu">
                <?php include Kohana::find_file('views', 'user/buildmenu') ?>
            </div>
            <?php include Kohana::find_file('views', 'common/footer') ?>
        </div>
    </body>
</html>