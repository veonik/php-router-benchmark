<?php

use TylerSommer\Nice\Benchmark\Benchmark;

/**
 * Sets up FastRoute tests
 */
function setupFastRoute(Benchmark $benchmark, $routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    $router = FastRoute\simpleDispatcher(function ($router) use ($routes, $argString, &$lastStr) {
            for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
                $router->addRoute('GET', '/' . $str . '/' . $argString, 'handler' . $i);
                $lastStr = $str;
            }
        });

//    $benchmark->register('FastRoute - first route', function () use ($router, $argString) {
//            $route = $router->dispatch('GET', '/a/' . $argString);
//        });

    $benchmark->register(sprintf('FastRoute - last route (%s routes)', $routes), function () use ($router, $lastStr, $argString) {
            $route = $router->dispatch('GET', '/' . $lastStr . '/' . $argString);
        });

    $benchmark->register(sprintf('FastRoute - unknown route (%s routes)', $routes), function () use ($router) {
            $route = $router->dispatch('GET', '/not-even-real');
        });
}

/**
 * Sets up Pux tests
 */
function setupPux(Benchmark $benchmark, $routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return ':arg' . $i; }, range(1, $args)));
    $lastStr = '';
    $router = new Pux\Mux;
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $router->add('/' . $str . '/' . $argString, 'handler' . $i);
        $lastStr = $str;
    }

//    $benchmark->register('Pux PHP - first route', function () use ($router, $argString) {
//            $route = $router->match('/a/' . $argString);
//        });

    $benchmark->register(sprintf('Pux PHP - last route (%s routes)', $routes), function () use ($router, $lastStr, $argString) {
            $route = $router->match('/' . $lastStr . '/' . $argString);
        });

    $benchmark->register(sprintf('Pux PHP - unknown route (%s routes)', $routes), function () use ($router) {
            $route = $router->match('GET', '/not-even-real');
        });
}

/**
 * Sets up Symfony 2 tests
 */
function setupSymfony2(Benchmark $benchmark, $routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    $sfRoutes = new Symfony\Component\Routing\RouteCollection();
    $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $sfRoutes->add($str, new Symfony\Component\Routing\Route('/' . $str . '/' . $argString, array('controller' => 'handler' . $i)));
        $lastStr = $str;
    }

//    $benchmark->register('Symfony2 - first route', function () use ($router, $argString) {
//            $route = $router->match('/a/' . $argString);
//        });

    $benchmark->register(sprintf('Symfony2 - last route (%s routes)', $routes), function () use ($router, $lastStr, $argString) {
            $route = $router->match('/' . $lastStr . '/' . $argString);
        });

    $benchmark->register(sprintf('Symfony2 - unknown route (%s routes)', $routes), function () use ($router) {
            try {
                $route = $router->match('/not-even-real');
            } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) { }
        });
}

/**
 * Sets up Symfony2 optimized tests
 */
function setupSymfony2Optimized(Benchmark $benchmark, $routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    $sfRoutes = new Symfony\Component\Routing\RouteCollection();
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $sfRoutes->add($str, new Symfony\Component\Routing\Route('/' . $str . '/' . $argString, array('controller' => 'handler' . $i)));
        $lastStr = $str;
    }
    $dumper = new Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper($sfRoutes);
    file_put_contents(__DIR__ . '/sf2router.php', $dumper->dump());
    require_once __DIR__ . '/sf2router.php';

    $router = new ProjectUrlMatcher(new Symfony\Component\Routing\RequestContext());

//    $benchmark->register('Symfony2 Dumped - first route', function () use ($router, $argString) {
//            $route = $router->match('/a/' . $argString);
//        });

    $benchmark->register(sprintf('Symfony2 Dumped - last route (%s routes)', $routes), function () use ($router, $lastStr, $argString) {
            $route = $router->match('/' . $lastStr . '/' . $argString);
        });

    $benchmark->register(sprintf('Symfony2 Dumped - unknown route (%s routes)', $routes), function () use ($router) {
            try {
                $route = $router->match('/not-even-real');
            } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) { }
        });
}
