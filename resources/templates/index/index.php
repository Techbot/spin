Hello.

<script>

    var connection = new WebSocket("ws://127.0.0.1:4002");

    connection.addEventListener("open", function(event) {
        console.log("Connection made");
    });

    connection.addEventListener("message", function(event) {
        console.log(event.data);
    });

    console.log("Waiting for connection...");

</script>
