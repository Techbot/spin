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

    connection.addEventListener('open', function(event) {
        console.log('Connection established!');
    });

    connection.addEventListener('message', function(event) {
        console.log(event.data);
    });

    console.log('waiting for connection...');

</script>";
    }
}
