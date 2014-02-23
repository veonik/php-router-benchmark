<?php
require __DIR__ . '/vendor/autoload.php';
$routes = 1000;
$args = 10;

setupFastRoute($routes, $args);
setupAuraRouterv2($routes, $args);
setupSymfony2($routes, $args);
/**
 * Sets up FastRoute tests
 */
function setupFastRoute($routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();
    $router = FastRoute\simpleDispatcher(function ($router) use ($routes, $argString, &$lastStr) {
            for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
                $router->addRoute('GET', '/' . $str . '/' . $argString, 'handler' . $i);
                $lastStr = $str;
            }
        });
    $route = $router->dispatch('GET', '/' . $lastStr . '/' . $argString);
    $time = PHP_Timer::stop();
    if ($route) {
        echo "Route found in fast route " . PHP_EOL;
    }
    echo " Found fast route at " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
    
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();
    $router = FastRoute\simpleDispatcher(function ($router) use ($routes, $argString, &$lastStr) {
            for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
                $router->addRoute('GET', '/' . $str . '/' . $argString, 'handler' . $i);
                $lastStr = $str;
            }
        });    
    $route = $router->dispatch('GET', '/not-even-real');
    $time = PHP_Timer::stop();
    if (! $route) {
        echo "Route found in fast route " . PHP_EOL;
    }
    echo " Not found fast route at " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
}

/**
 * Sets up Symfony 2 tests
 */
function setupSymfony2($routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();
    $sfRoutes = new Symfony\Component\Routing\RouteCollection();
    $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $sfRoutes->add($str, new Symfony\Component\Routing\Route(
            '/' . $str . '/' . $argString, 
            array(
                'controller' => function () use ($i) {
                    echo ' From the controller inside symfony' . $i . PHP_EOL;
                }
            )
        ));
        $lastStr = $str;
    }
    $route = $router->match('/' . $lastStr . '/' . $argString); 
    $time = PHP_Timer::stop();
    if ($route) {
        echo "Route found in symfony" . PHP_EOL;
        echo $route['controller']();
    }
    echo " Found symfony route at " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
    
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();    
    $sfRoutes = new Symfony\Component\Routing\RouteCollection();
    $router = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $sfRoutes->add($str, new Symfony\Component\Routing\Route(
            '/' . $str . '/' . $argString, 
            array(
                'controller' => function () use ($i) {
                    echo ' From the controller inside symfony' . $i . PHP_EOL;
                }
            )
        ));
        $lastStr = $str;
    }
    try {
        $route = $router->match('/not-even-real');
    } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) { 
        $time = PHP_Timer::stop();
        echo " Route not found symfony route at " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
    }
}

function setupAuraRouterv2($routes, $args)
{
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();
    $router = new Aura\Router\Router(
        new Aura\Router\RouteCollection(
            new Aura\Router\RouteFactory()
        )
    );
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $router->add('handler' . $i, '/' . $str . '/' . $argString)
            ->addValues(array(
                'controller' => function () use ($i) {
                    echo ' From the controller inside aura ' . $i . PHP_EOL;
                }
            ));
        $lastStr = $str;
    }
    
    $route = $router->match('/' . $lastStr . '/' . $argString, $_SERVER);
    $time = PHP_Timer::stop();
    if ($route) {
       echo "Route found in aura " . PHP_EOL;
       echo $route->params['controller']();
    } else {
        echo '/' . $lastStr . '/' . $argString . PHP_EOL;
    }
    echo " Found aura route at " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
    
    $argString = implode('/', array_map(function ($i) { return "{arg$i}"; }, range(1, $args)));
    $lastStr = '';
    PHP_Timer::start();
    $router = new Aura\Router\Router(
        new Aura\Router\RouteCollection(
            new Aura\Router\RouteFactory()
        )
    );
    for ($i = 0, $str = 'a'; $i < $routes; $i++, $str++) {
        $router->add('handler' . $i, '/' . $str . '/' . $argString)
            ->addValues(array(
                'controller' => function () use ($i) {
                    echo ' From the controller inside aura ' . $i . PHP_EOL;
                }
            ));
        $lastStr = $str;
    }
    $route = $router->match('/not-even-real', $_SERVER);
    $time = PHP_Timer::stop();
    if (! $route) {
        echo " Route not found in " . PHP_Timer::secondsToTimeString($time) . PHP_EOL;
    }    
}
