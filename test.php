<?php

error_reporting(E_ALL);

require __DIR__ . '/vendor/autoload.php';

function callback() {}

/**
 * A Simple Operation Benchmark Class
 *
 * @author  Tyler Sommer
 * @license WTFPL
 */
class Benchmark
{
    protected $_length;

    protected $_tests = array();

    protected $_results = array();

    /**
     * @param int $length The number of iterations per test
     */
    public function __construct($length = 1000)
    {
        $this->_length = $length;
    }

    /**
     * Register a test
     *
     * @param string   $name     (Friendly) Name of the test
     * @param callback $callback A valid callback
     */
    public function register($name, $callback)
    {
        $this->_tests[$name] = $callback;
    }

    /**
     * Execute the registered tests and display the results
     */
    public function execute()
    {
        $adjustment = round($this->_length * .1, 0);
        echo "Running " . count($this->_tests) . " tests, {$this->_length} times each...\nThe {$adjustment} highest and lowest results will be disregarded.\n\n";
        foreach ($this->_tests as $name => $test) {
            $results = array();
            for ($x = 0; $x < $this->_length; $x++) {
                ob_start();
                $start = time() + microtime();
                call_user_func($test);
                $results[] = round((time() + microtime()) - $start, 10);
                ob_end_clean();
            }
            sort($results);
            // remove the lowest and highest 10% (leaving 80% of results)
            for ($x = 0; $x < $adjustment; $x++) {
                array_shift($results);
                array_pop($results);
            }
            $avg = array_sum($results) / count($results);
            echo "For {$name}, out of " . count($results) . " runs, average time was: " . sprintf(
                    "%.10f",
                    $avg
                ) . " secs.\n";
            $this->_results[$name] = $avg;
        }
        asort($this->_results);
        reset($this->_results);
        $fastestResult = each($this->_results);
        reset($this->_results);
        echo "\n\nResults:\n";
        printf("%-35s\t%-20s\t%-20s\t%s\n", "Test Name", "Time", "+ Interval", "Change");
        foreach ($this->_results as $name => $result) {
            $interval = $result - $fastestResult["value"];
            $change   = round((1 - $result / $fastestResult["value"]) * 100, 0);
            if ($change == 0) {
                $change = 'baseline';
            } else {
                $faster = true; // Cant really ever be faster, now can it
                if ($change < 0) {
                    $faster = false;
                    $change *= -1;
                }
                $change .= '% ' . ($faster ? 'faster' : 'slower');
            }
            printf(
                "%-35s\t%-20s\t%-20s\t%s\n",
                $name,
                sprintf("%.10f", $result),
                "+" . sprintf("%.10f", $interval),
                $change
            );
        }
    }
}

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
$sfMatcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($sfRoutes, new \Symfony\Component\Routing\RequestContext());

$benchmark = new Benchmark();
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

