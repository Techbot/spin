<?php

namespace App\Http\Handler;

class IndexHandler
{
    /**
     * @return string
     */
    public function index()
    {
        return "<script>

    var connection = new WebSocket('ws://127.0.0.1:4002');

    connection.addEventListener('open', function(e) {
        console.log('Connection established!');
    });

    connection.addEventListener('message', function(e) {
        console.log(e.data);
    });

    console.log('waiting for connection...');

</script>";
    }
}
