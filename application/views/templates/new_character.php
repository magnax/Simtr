<html>
    <head>
        <?php include Kohana::find_file('views', 'common/header') ?>
        
        <script src="http://192.168.1.7:8011/socket.io/socket.io.js"></script>
        <script src="http://code.jquery.com/jquery-1.8.0.min.js"></script>
        <script>
            var socket = io.connect('http://192.168.1.7:8011');
            var char_id = <?= $character['id']; ?>;
            socket.on('connect', function(data) {
                console.log('connected');
                socket.on('auth', function(incoming) {
                    console.log('received auth request');
                    socket.emit('check in', {'char_id': char_id})
                });
            });
            socket.on('events', function (data) {
                console.log('got data');
                console.log(data.text);
              });
            
            $(document).ready(function() {
                console.log('started app');
                $('#talk_all').submit(function(event) {
                    event.preventDefault(); 
                    var text = $('#event_input_small').val();
                    $.post('/events/talkall', {'text':text}, function(data) {
                        $('#event_input_small').val('');
                    });
                });
            });
        </script>
    </head>
    <body>
        <div id="game_info">Info about game (current date/time (<div id="time">??</div>), info active users)</div>
        <div id="user_info">Info about user</div>
        <?php echo Form::open('#', array('id'=>'talk_all')); ?>
        <input id="event_input_small" type="text" name="text"> <input id="talk" type="submit" value="Talk to all">
        <?php echo Form::close(); ?>
        <div id="events">Events</div>
        <div id="inventory">User inventory (maybe with tabs)</div>
        <div id="location">Location info (map, resources, animals)</div>
        <div id="roads">Roads</div>
        <div id="buildings">Buildings</div>
        <div id="vehicles">Vehicles</div>
        <div id="people">People</div>
        <div id="objects">Objects on the ground (notes, resources, tools)</div>
        <div id="machines">Machines to use</div>
        <div id="projects">Info about projects</div>
    </body>
</html>