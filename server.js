var app = require('express')();
var http = require('http').Server(app);
var io = require("socket.io")(http, {
    cors: {
        origin: "http://proje.com",
        methods: ["GET", "POST"]
    }
});

app.get('/', function (req,res) {
    res.send('Burası Anasayfa');
});

io.on('connection',(socket)=>{
   socket.on('yeni_randevu_olustur', function () {
       io.emit("admin_randevu_listesi");
   });
});

http.listen(3000, function () {
    console.log("Server çalıştı");
});