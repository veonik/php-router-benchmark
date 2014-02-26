<?php

use TylerSommer\Nice\Benchmark\Benchmark;

function getRandomParts()
{
    $rand = md5(uniqid(mt_rand(), true));
    return array(
        substr($rand, 0, 10),
        substr($rand, -10),
    );
}

function getRoutes($numRoutes, $argString)
{
    $routes = [];
    for ($i = 0; $i < $numRoutes; $i++) {
        list ($pre, $post) = getRandomParts();
        $route = '/' . $pre . '/' . $argString . '/' . $post;
        $routes[] = $route;
    }

    return $routes;
}

/**
 * Sets up FastRoute tests
 */
function setupFastRoute(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    // $benchmark->register(sprintf('FastRoute - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = FastRoute\simpleDispatcher(function ($router) use ($routes) {
    //         foreach ($routes as $i => $route) {
    //             $router->addRoute('GET', $route, 'handler' . $i);
    //         }
    //     });

    //     $route = $router->dispatch('GET', $firstStr);
    // });

    $benchmark->register(sprintf('FastRoute - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = FastRoute\simpleDispatcher(function ($router) use ($routes) {
            foreach ($routes as $i => $route) {
                $router->addRoute('GET', $route, 'handler' . $i);
            }
        });

        $route = $router->dispatch('GET', $lastStr);
    });

    $benchmark->register(sprintf('FastRoute - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = FastRoute\simpleDispatcher(function ($router) use ($routes) {
            foreach ($routes as $i => $route) {
                $router->addRoute('GET', $route, 'handler' . $i);
            }
        });

        $route = $router->dispatch('GET', '/not-even-real');
    });
}

/**
 * Sets up Pux tests
 */
function setupPux(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return ':arg' . $i; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    // $benchmark->register(sprintf('Pux PHP - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = new Pux\Mux;
    //     foreach ($routes as $i => $route) {
    //         $router->add($route, 'handler' . $i);
    //     }
    //     $route = $router->match($firstStr);
    // });

    $benchmark->register(sprintf('Pux PHP - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = new Pux\Mux;
        foreach ($routes as $i => $route) {
            $router->add($route, 'handler' . $i);
        }
        $route = $router->match($lastStr);
    });

    $benchmark->register(sprintf('Pux PHP - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = new Pux\Mux;
        foreach ($routes as $i => $route) {
            $router->add($route, 'handler' . $i);
        }
        $route = $router->match('GET', '/not-even-real');
    });
}

/**
 * Sets up Symfony 2 tests
 */
function setupSymfony2(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));

    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    // $benchmark->register(sprintf('Symfony2 - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $sfRoutes = new Symfony\Component\Routing\RouteCollection();
    //     $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
    //     foreach ($routes as $i => $route) {
    //         $sfRoutes->add($route, new Symfony\Component\Routing\Route($route, array('controller' => 'handler' . $i)));
    //     }
    //     $route = $router->match($firstStr);
    // });

    $benchmark->register(sprintf('Symfony2 - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $sfRoutes = new Symfony\Component\Routing\RouteCollection();
        $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
        foreach ($routes as $i => $route) {
            $sfRoutes->add($route, new Symfony\Component\Routing\Route($route, array('controller' => 'handler' . $i)));
        }
        $route = $router->match($lastStr);
    });

    $benchmark->register(sprintf('Symfony2 - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        try {
            $sfRoutes = new Symfony\Component\Routing\RouteCollection();
            $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
            foreach ($routes as $i => $route) {
                $sfRoutes->add($route, new Symfony\Component\Routing\Route($route, array('controller' => 'handler' . $i)));
            }
            $route = $router->match('/not-even-real');
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) { }
    });
}

/**
 * Sets up Aura v2 tests
 *
 * https://github.com/auraphp/Aura.Router
 */
function setupAura2(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    // $benchmark->register(sprintf('Aura v2 - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = new Aura\Router\Router(
    //         new Aura\Router\RouteCollection(
    //             new Aura\Router\RouteFactory()
    //         )
    //     );

    //     foreach ($routes as $i => $route) {
    //         $router->add($route, $route)
    //             ->addValues(array(
    //                 'controller' => 'handler' . $i
    //             ));
    //     }

    //     $route = $router->match($firstStr, $_SERVER);
    // });

    $benchmark->register(sprintf('Aura v2 - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
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

        $route = $router->match($lastStr, $_SERVER);
    });

    $benchmark->register(sprintf('Aura v2 - unknown route (%s routes)', $numRoutes), function () use ($routes) {
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

        $route = $router->match('/not-even-real', $_SERVER);
    });
}

/**
 * Sets up AdamBrett\Router
 *
 * https://github.com/adambrett/php-router
 */
function setupABRouter(Benchmark $benchmark, $numRoutes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $routes = getRoutes($numRoutes, $argString);
    $firstStr = $routes[0];
    $lastStr = $routes[$numRoutes - 1];

    // $benchmark->register(sprintf('AdamBrett\\Router - first route (%s routes)', $numRoutes), function () use ($routes, $firstStr) {
    //     $router = new AdamBrett\Router\FactoryRouter(
    //         new AdamBrett\Router\Router('GET', $firstStr)
    //     );

    //     foreach ($routes as $i => $route) {
    //         $router->get($route, function () use ($i) {
    //             if (function_exists('handle' . $i)) {
    //                 call_user_func('handle' . $i);
    //             }
    //         });
    //     }

    //     $router->dispatch();
    // });

    $benchmark->register(sprintf('AdamBrett\\Router - last route (%s routes)', $numRoutes), function () use ($routes, $lastStr) {
        $router = new AdamBrett\Router\FactoryRouter(
            new AdamBrett\Router\Router('GET', $lastStr)
        );

        foreach ($routes as $i => $route) {
            $router->get($route, function () use ($i) {
                if (function_exists('handle' . $i)) {
                    call_user_func('handle' . $i);
                }
            });
        }

        $router->dispatch();
    });

    $benchmark->register(sprintf('AdamBrett\\Router - unknown route (%s routes)', $numRoutes), function () use ($routes) {
        $router = new AdamBrett\Router\FactoryRouter(
            new AdamBrett\Router\Router('GET', '/not-even-real')
        );

        foreach ($routes as $i => $route) {
            $router->get($route, function () use ($i) {
                if (function_exists('handle' . $i)) {
                    call_user_func('handle' . $i);
                }
            });
        }

        $router->dispatch();
    });
}
