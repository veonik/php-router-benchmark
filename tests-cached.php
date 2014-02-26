<?php

use TylerSommer\Nice\Benchmark\Benchmark;

require __DIR__ . '/tests.php';

define('CACHE_DIR', __DIR__ . '/.cache');

if (!is_dir(CACHE_DIR)) {
    mkdir(CACHE_DIR, 0777, true);
}

/**
 * Sets up FastRoute dumped
 */
function setupFastRouteCached(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    $router = FastRoute\simpleDispatcher(function ($router) use ($routes) {
        foreach ($routes as $i => $route) {
            $router->addRoute('GET', $route, 'handler' . $i);
        }
    });

    file_put_contents(CACHE_DIR . '/fastroute.php', serialize($router));
    // $benchmark->register(sprintf('FastRoute Cached - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = unserialize(file_get_contents(CACHE_DIR . '/fastroute.php'));
    //     $route = $router->dispatch('GET', $firstStr);
    // });

    $benchmark->register(sprintf('FastRoute Cached - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/fastroute.php'));
        $route = $router->dispatch('GET', $lastStr);
    });

    $benchmark->register(sprintf('FastRoute Cached - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/fastroute.php'));
        $route = $router->dispatch('GET', '/not-even-real');
    });
}

/**
 * Sets up Symfony2 optimized tests
 */
function setupSymfony2Optimized(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    $sfRoutes = new Symfony\Component\Routing\RouteCollection();

    foreach ($routes as $i => $route) {
        $sfRoutes->add($route, new Symfony\Component\Routing\Route($route, array('controller' => 'handler' . $i)));
    }

    $dumper = new Symfony\Component\Routing\Matcher\Dumper\PhpMatcherDumper($sfRoutes);
    file_put_contents(CACHE_DIR . '/sf2router.php', $dumper->dump());

    require_once CACHE_DIR . '/sf2router.php';
    // $benchmark->register(sprintf('Symfony2 Dumped - first route (%s routes)', $numRoutes), function () use ($firstStr) {
    //     $router = new ProjectUrlMatcher(new Symfony\Component\Routing\RequestContext());
    //     $route = $router->match($firstStr);
    // });

    $benchmark->register(sprintf('Symfony2 Dumped - last route (%s routes)', $numRoutes), function () use ($lastStr) {
        $router = new ProjectUrlMatcher(new Symfony\Component\Routing\RequestContext());
        $route = $router->match($lastStr);
    });

    $benchmark->register(sprintf('Symfony2 Dumped - unknown route (%s routes)', $numRoutes), function () {
        try {
            $router = new ProjectUrlMatcher(new Symfony\Component\Routing\RequestContext());
            $route = $router->match('/not-even-real');
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) { }
    });
}

/**
 * Sets up Pux tests
 */
function setupPuxCached(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return ':arg' . $i; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    $router = new Pux\Mux;
    foreach ($routes as $i => $route) {
        $router->add($route, 'handler' . $i);
    }

    file_put_contents(CACHE_DIR . '/pux.php', serialize($router));
    // $benchmark->register(sprintf('Pux PHP Cached - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = unserialize(file_get_contents(CACHE_DIR . '/pux.php'));
    //     $route = $router->match($firstStr);
    // });

    $benchmark->register(sprintf('Pux PHP Cached - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/pux.php'));
        $route = $router->match($lastStr);
    });

    $benchmark->register(sprintf('Pux PHP Cached - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/pux.php'));
        $route = $router->match('GET', '/not-even-real');
    });
}

/**
 * Sets up Aura v2 tests
 *
 * https://github.com/auraphp/Aura.Router
 */
function setupAura2Cached(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    $router = new Aura\Router\Router(
        new Aura\Router\RouteCollection(
            new Aura\Router\RouteFactory()
        )
    );

    foreach ($routes as $i => $route) {
        $router->add($route, $route)
            ->addValues(array(
                'controller' => 'handler' . $i
            ));
    }

    file_put_contents(CACHE_DIR . '/aura2.php', serialize($router));


    // $benchmark->register(sprintf('Aura v2 - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = unserialize(file_get_contents(CACHE_DIR . '/aura2.php'));
    //     $route = $router->match($firstStr, $_SERVER);
    // });

    $benchmark->register(sprintf('Aura v2 Cached - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/aura2.php'));
        $route = $router->match($lastStr, $_SERVER);
    });

    $benchmark->register(sprintf('Aura v2 Cached - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = unserialize(file_get_contents(CACHE_DIR . '/aura2.php'));
        $route = $router->match('/not-even-real', $_SERVER);
    });
}
