<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

// $channel->exchange_delete('elo');
// $channel->queue_delete('registros');
// $channel->queue_delete('processamento');

$channel->exchange_declare('elo', 'direct', false, false, false);

$channel->queue_declare('registros', false, false, false, false);
$channel->queue_declare('processamento', false, false, false, false);

$channel->queue_bind('registros', 'elo');
$channel->queue_bind('processamento', 'elo');

for($i = 0; $i < 100; $i++) {
    $msg = new AMQPMessage("Hello {$i}"); 
    $channel->basic_publish($msg, 'elo');
}

echo " [x] Sent 'Hello World!'\n";

$channel->close();
$connection->close();
?>