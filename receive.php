<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();
//$channel->queue_delete('processamento');
$channel->queue_declare('processamento', false, false, false, false);

echo " [*] Waiting for messages. To exit press CTRL+C\n";

$callback = function ($msg) {
    echo ' [x] Received ', $msg->body, "\n";
    $msg->nack(true);
    if (true) {
        // return to the queue 
        //$msg->nack(true);
    }else{
        // send ack , remove from queue
        //$msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
    }
};

$channel->basic_qos(0, 1, false);
$channel->basic_consume('processamento', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
?>