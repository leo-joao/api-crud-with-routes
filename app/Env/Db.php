<?php

// prod or dev
$environment = 'prod';

if ($environment === 'prod') {
  define('DB_TYPE', 'mysql');
  define('DB_HOST', '193.203.175.56');
  define('DB_NAME', 'u680765307_softExpert');
  define('DB_USER', 'u680765307_leojoaoSoft');
  define('DB_PASS', 'IgJW3m4z&1bV');
} elseif ($environment === 'dev') {
  define('DB_TYPE', 'pgsql');
  define('DB_HOST', 'localhost');
  define('DB_NAME', 'SimpleShop');
  define('DB_USER', 'postgres');
  define('DB_PASS', '123456');
}
