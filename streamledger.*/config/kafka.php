<?php

return [
  'brokers' => env('KAFKA_BROKERS', '127.0.0.1:9092'),
  'topic' => env('KAFKA_TOPIC', 'transactions'),
];
