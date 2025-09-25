<?php

namespace RdKafka {
  
  class Producer
  {
    public function newTopic(string $name) {}
    public function flush(int $timeout) {}
  }

  class Conf
  {
    public function set(string $key, string $value) {}
  }

  class ProducerTopic
  {
    public function produce(int $partition, int $msgflags, string $payload) {}
  }

}
