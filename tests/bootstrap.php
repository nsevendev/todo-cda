<?php

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Dotenv\Dotenv;
use Tocda\Kernel;

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->usePutenv()->overload(dirname(__DIR__).'/.env.dev.local');
$dotenv->usePutenv()->overload(dirname(__DIR__).'/.env.test.local');

$kernel = new Kernel('test', true);
$kernel->boot();

$application = new Application($kernel);
$application->setAutoExit(false);

$kernel->shutdown();
