<?php

namespace App\Services;

use RdKafka\Producer;
use RdKafka\Conf;

class KafkaProducer
{
  protected Producer $producer;
  protected string $topic;

  public function __construct()
  {
    $conf = new Conf();
    $conf->set('metadata.broker.list', config('kafka.brokers'));

    $this->producer = new Producer($conf);
    $this->topic = config('kafka.topic');
  }

  public function produce(string $message): void
  {
    $topic = $this->producer->newTopic($this->topic);
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $message);

    // flush messages
    for ($i = 0; $i < 3; $i++) {
      $result = $this->producer->flush(1000);
      if ($result === RD_KAFKA_RESP_ERR_NO_ERROR) {
        return;
      }
    }

    throw new \RuntimeException('Failed to flush Kafka messages');
  }
}
