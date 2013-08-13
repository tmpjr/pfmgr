<?php

// file_put_contents('/Users/guachiman/Sites/pfmgr/resources/log/app.log', "####\n".file_get_contents("php://input")."###\n", FILE_APPEND);
// die;

$app = require __DIR__ . '/../../app/app.php';

$app->run();