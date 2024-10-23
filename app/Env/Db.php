<?php

// prod or dev
$environment = 'prod';

if ($environment === 'prod') {
  define('DB_TYPE', 'mysql');
  define('DB_HOST', '');
  define('DB_NAME', '');
  define('DB_USER', '');
  define('DB_PASS', '');
} elseif ($environment === 'dev') {
  define('DB_TYPE', 'pgsql');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'SimpleShop');
  define('DB_USER', 'postgres');
  define('DB_PASS', '123456');
}
