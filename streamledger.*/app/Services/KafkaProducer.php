<?php

namespace App\Services;

use RdKafka\Producer;
use RdKafka\Conf;
use RdKafka\ProducerTopic;
use Throwable;

class KafkaProducer
{
  public const PARTITION_UA = "RD_KAFKA_PARTITION_UA";

  protected Producer $producer;
  protected ProducerTopic $topic;

  public function __construct()
  {
    $conf = new Conf();
    $conf->set('metadata.broker.list', config('kafka.brokers'));

    $this->producer = new Producer($conf);

    /** @var ProducerTopic $topic */
    $topic = $this->producer->newTopic(config('kafka.topic'));
    $this->topic = $topic; // now Intelephense knows it's ProducerTopic
  }

  /**
   * $message should be a string (JSON)
   */
  public function produce(string $message): void
  {
    try {
      $this->topic->produce(self::PARTITION_UA, 0, $message);
      $this->producer->flush(1000);
    } catch (Throwable $e) {
      logger()->error('Kafka produce error: ' . $e->getMessage(), ['exception' => $e]);
    }
  }
}
