<?php

error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

function callback() {}

$options = [];

$nRoutes = 1000;
$nArgs = 9;
$nMatches = 1;

// FastRoute
$frArgs = implode('/', array_map(function($i) { return "{arg$i}"; }, range(1, $nArgs)));
$frLastStr = '';
$router = FastRoute\simpleDispatcher(function($router) use($nRoutes, $frArgs, &$frLastStr) {
        for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
            $router->addRoute('GET', '/' . $str . '/' . $frArgs, 'handler' . $i);
            $frLastStr = $str;
        }
    }, $options);

// Pux\Mux
$muxArgs = implode('/', array_map(function($i) { return ':arg' . $i; }, range(1, $nArgs)));
$muxLastStr = '';
$mux = new Pux\Mux;
for ($i = 0, $str = 'a'; $i < $nRoutes; $i++, $str++) {
    $mux->add('/' . $str . '/' . $muxArgs, 'callback');
    $muxLastStr = $str;
}

// Symfony 2
$sfArgs = implode('/', array_map(function($i) { return "{arg$i}"; }, range(1, $nArgs)));
$sfLastStr = '';
$sfRoutes = new Symfony\Component\Routing\RouteCollection();
for ($x = 0, $str = 'a'; $x < $nRoutes; $x++, $str++) {
    $sfRoutes->add($str, new Symfony\Component\Routing\Route('/' . $str . '/' . $sfArgs, array('controller' => 'callback')));
    $sfLastStr = $str;
}
$sfMatcher = new Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new Symfony\Component\Routing\RequestContext());

$benchmark = new TylerSommer\Nice\Benchmark\Benchmark();
//$benchmark->register('FastRoute - first route', function() use($nMatches, $router, $frArgs) {
//        for ($i = 0; $i < $nMatches; $i++) {
//            $res = $router->dispatch('GET', '/a/' . $frArgs);
//        }
//    });
//
//$benchmark->register('Pux PHP - first route', function() use($nMatches, $mux, $muxArgs) {
//        for ($i = 0; $i < $nMatches; ++$i) {
//            $route = $mux->dispatch('/a/' . $muxArgs);
//        }
//    });
//
//$benchmark->register('Symfony 2 - first route', function() use($nMatches, $sfMatcher, $sfArgs) {
//        for ($i = 0; $i < $nMatches; ++$i) {
//            $route = $sfMatcher->match('/a/' . $sfArgs);
//        }
//    });

$benchmark->register(sprintf('FastRoute - last route (%s routes)', $nRoutes), function() use($nMatches, $router, $frLastStr, $frArgs) {
        for ($i = 0; $i < $nMatches; $i++) {
            $res = $router->dispatch('GET', '/' . $frLastStr . '/' . $frArgs);
        }
    });

$benchmark->register(sprintf('Pux PHP - last route (%s routes)', $nRoutes), function() use($nMatches, $mux, $muxLastStr, $muxArgs) {
        for ($i = 0; $i < $nMatches; ++$i) {
            $route = $mux->dispatch('/' . $muxLastStr . '/' . $muxArgs);
        }
    });

$benchmark->register(sprintf('Symfony 2 - last route (%s routes)', $nRoutes), function() use($nMatches, $sfMatcher, $sfLastStr, $sfArgs) {
        for ($i = 0; $i < $nMatches; ++$i) {
            $route = $sfMatcher->match('/' . $sfLastStr . '/' . $sfArgs);
        }
    });

$benchmark->execute();

