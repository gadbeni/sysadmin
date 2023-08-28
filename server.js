const fs = require("fs");

// const server = require('https').createServer({
//   key: fs.readFileSync("/etc/letsencrypt/live/example.com/privkey.pem"),
//   cert: fs.readFileSync("/etc/letsencrypt/live/example.com/fullchain.pem")
// });

const server = require('http').createServer();

const io = require('socket.io')(server, {
    cors: { origin: "*"}
});


io.on('connection', (socket) => {
    console.log('connection');

    socket.on('set new ticket', data => {
        io.emit(`get new ticket`, data);
    });

    socket.on('disconnect', (socket) => {
        console.log('Disconnect');
    });
});

server.listen(3001, () => {
    console.log('Server Socket.io is running');
});