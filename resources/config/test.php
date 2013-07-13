<?php

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;

// Doctrine DBAL (db)
$app['db.options'] = array(
    'driver'   => 'pdo_sqlite',
    'path' => __DIR__ . '/test.db'
);
