#!/usr/bin/env php
<?php
// Make sure that script will continue to run
set_time_limit(0);

// Let's get our footing so we know where we are at

require __DIR__ . '/vendor/autoload.php';

use ServerTools\Application;

$application = new Application();
$application->run();
