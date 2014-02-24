<?php

use TylerSommer\Nice\Benchmark\Benchmark;
use TylerSommer\Nice\Benchmark\ResultPrinter\MarkdownPrinter;

error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/tests.php';

$iterations = 1000;
$routes = 1000;
$args = 9;

$benchmark = new Benchmark($iterations, new MarkdownPrinter());

setupAura2($benchmark, $routes, $args);
setupFastRoute($benchmark, $routes, $args);
setupSymfony2($benchmark, $routes, $args);
setupPux($benchmark, $routes, $args);
setupABRouter($benchmark, $routes, $args);

$benchmark->execute();
