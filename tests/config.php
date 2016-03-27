<?php
$config = require __DIR__ . '/../config.php';
$config['database']['connections']['default']['prefix'] = 'test_';
return $config;
