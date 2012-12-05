var app = require('http').createServer(handler)
  , io = require('socket.io').listen(app)
  , fs = require('fs')
  , qs = require('querystring')

app.listen(8000);


io.sockets.on('connection', function (socket) {

  socket.on('newpost', function (posterid, posttitle,courseid,postid) {
    console.log('New post by ', posterid, ' regarding: ', posttitle);
    socket.emit('pullnewpost',posterid,posttitle,courseid,postid);
  });

});

function handler(req, res) {
    // set up some routes
      fs.readFile(__dirname + '/update_stream.html',
      function (err, data) {
        if (err) {
          res.writeHead(500);
          return res.end('Error loading index.html');
        }

        res.writeHead(200);
        res.end(data);
      })
}
