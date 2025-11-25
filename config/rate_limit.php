<?php

return [
  'default' => [
    'max_attempts' => 60,
    'decay_minutes' => 1,
    'block_minutes' => 60,
  ],

  'strict' => [
    'max_attempts' => 30,
    'decay_minutes' => 1,
    'block_minutes' => 120,
  ],

  // Unbanneable ips
  'whitelist' => [
    '127.0.0.1',
    '192.168.1.1',
  ],

  'blacklist' => [
  ],
];