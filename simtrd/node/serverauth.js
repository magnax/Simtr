//var http = require('http');
var sio = require('socket.io');
var fs = require('fs');
var redis = require("./lib/redis-client").createClient();

// Start the server
//var server = http.createServer(function(req, res){ 
//    res.writeHead(200,{ 'Content-Type': 'text/html' }); 
//    res.end('<h1>Hello Socket Lover!</h1>');
//});
//
//server.listen(8000);

// Create a Socket.IO instance, passing it port
var io = sio.listen(8000);

var characters = {};
var connected_chars = {};
var users = {};
var connected_users = {};

var timeFile = '/home/magnax/www/simtrd/counter';

console.log('version 0.0.1 started');

io.sockets.on('connection', function(socket) {
    console.log('connected');
    socket.emit('auth');
    
    socket.on('check in', function(data) {
        console.log('received check in from ' + data.char_id + ' (' + socket.id +')');
        characters[data.char_id] = socket.id;
        connected_chars[socket.id] = data.char_id;
        console.log(characters);
        redis.set("connected_char:" + data.char_id, socket.id, function() {
            console.log('connected char saved');
        })
    });
    
    socket.on('user check in', function(data) {
        console.log('received user check in from ' + data.user_id + ' (' + socket.id +')');
        users[data.user_id] = socket.id;
        connected_users[socket.id] = data.user_id;
        console.log(users);
        redis.set("connected_user:" + data.user_id, socket.id, function() {
            console.log('connected user saved');
        })
    });
    
    socket.on('push_event', function(data) {
        console.log('event pushed (' + socket.id + ')');
        var socket_id = characters[data.char_id];
        if (socket_id) {
            console.log('sended to '+socket_id);
            io.sockets.sockets[socket_id].emit('events', data);
        } else {
            console.log ('theres no registered user of '+data.char_id);
        }
    });
    
    socket.on('push_user_event', function(data) {
        var socket_id = users[data.user_id];
        if (socket_id) {
            redis.llen("new_events:"+data.char_id, function(err, data) {
                console.log("new_events:"+data.char_id+' = '+data);
                console.log('sended to '+socket_id);
                io.sockets.sockets[socket_id].emit('user_events', {'char_id': data.char_id, 'new': data});
            });
        } else {
            console.log ('theres no registered user of '+data.user_id);
        } 
    });
    
    fs.watch(timeFile, function ( curr, prev ) {
        var time = fs.readFile(timeFile, 'utf-8', function(err, time) {
            if (time && (time % 5 == 0)) {
                socket.emit('time', {'time': time});
            }
        });        
    });
    
    socket.on('disconnect', function() {
        if (connected_users[socket.id]) {
            redis.del("connected_user:"+connected_users[socket.id], function() {
                console.log('disconnected user saved');
            });
        } else if (connected_chars[socket.id]) {
            redis.del("connected_char:"+connected_chars[socket.id], function() {
                console.log('disconnected char saved');
            });
        }
    })
  
});
