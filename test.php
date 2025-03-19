<?php

require 'vendor/autoload.php';  // Load Predis

use Predis\Client;

try {
  $client = new Client([
    'host' => 'redis-17608.c292.ap-southeast-1-1.ec2.redns.redis-cloud.com',
    'port' => 17608,
    'database' => 0,
    'username' => 'default', // Redis Cloud requires a username
    'password' => '12PWGpPkVqCqUFpu7UPfBldQjH4uq61J', // Your Redis Cloud password
  ]);

  // Test setting and getting a key
  $client->set('foo', 'bar');
  $result = $client->get('foo');

  echo "Redis Connection Successful! Value: $result\n";  // Should print "bar"
} catch (Exception $e) {
  echo "Redis Connection Failed: " . $e->getMessage() . "\n";
}
