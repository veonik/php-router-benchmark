<?php

use TylerSommer\Nice\Benchmark\Benchmark;
use TylerSommer\Nice\Benchmark\ResultPrinter\MarkdownPrinter;

error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/tests-cached.php';

$iterations = 1000;
$routes = 1000;
$args = 9;

$benchmark = new Benchmark($iterations, new MarkdownPrinter());

setupFastRouteCached($benchmark, $routes, $args);
setupSymfony2Optimized($benchmark, $routes, $args);
setupAura2Cached($benchmark, $routes, $args);
setupPuxCached($benchmark, $routes, $args);
setupABRouter($benchmark, $routes, $args);

$benchmark->execute();
