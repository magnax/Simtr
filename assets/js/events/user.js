socket.on('connect', function(data) {

    socket.on('auth', function(incoming) {
        console.log('received auth request');
        socket.emit('user check in', {'user_id': user_id});
        $('#time').removeClass('error');
    });

    socket.on('time', function (data) {
        $('#time').html(decodeRawTime(data.time));

        for (p in projects) {
            if (projects[p].hasOwnProperty('time_zero')) {
                var now = (projects[p].time_elapsed + ((data.time - projects[p].time_zero) * projects[p].speed)) / projects[p].time * 100;                  
                if (now >= 100) {
                    $('#project-'+p).html('<b>100%</b>');
                    project = {};
                } else {
                    $('#project-'+p).html(Math.round(now*100)/100 + '%');
                }
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
        //console.log('connected chars: '+data.charcount);
        $('#count_active_chars').html(data.charcount); 
    });

    socket.on('user_events', function (data) {
        //console.log('received user event: '+data.char_id);
        var char_id = data.char_id;
        $('#character-'+char_id+' .character_events').html(data['new'] + ' new');
    });

    socket.on('disconnect', function () {
        console.log('disconnected!');
        $('#time').addClass('error');
    });

});