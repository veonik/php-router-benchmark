PHP Router Benchmark
====================

The intent here is to benchmark different PHP routing solutions. This is a micro-optimization, done purely out of 
dumb curiosity.


Installation
------------

Clone the repo, run `composer install`, run `php run-tests.php`.

You can install the Pux extension to test that as well. See Pux docs for more info.


Currently
---------

The current test creates 1000 routes, each with a randomized prefix and postfix, with 9 parameters each.

It was run with the [Pux](https://github.com/c9s/pux) and [r3](https://github.com/c9s/php-r3) extensions enabled.

An example route: `/9b37eef21e/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/bda37e9f9b`

## Worst-case matching
This benchmark matches the last route and unknown route. It generates a randomly prefixed and suffixed route in an attempt to thwart any optimization. 1,000 routes each with 9 arguments.

This benchmark consists of 12 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
r3 - unknown route (1000 routes) | 987 | 0.0000111161 | +0.0000000000 | baseline
r3 - last route (1000 routes) | 994 | 0.0000135476 | +0.0000024316 | 22% slower
FastRoute - unknown route (1000 routes) | 982 | 0.0003966292 | +0.0003855132 | 3468% slower
FastRoute - last route (1000 routes) | 999 | 0.0004029198 | +0.0003918037 | 3525% slower
Pux ext - unknown route (1000 routes) | 984 | 0.0008801297 | +0.0008690136 | 7818% slower
Symfony2 Dumped - unknown route (1000 routes) | 981 | 0.0009883075 | +0.0009771914 | 8791% slower
Pux ext - last route (1000 routes) | 999 | 0.0009942575 | +0.0009831414 | 8844% slower
Symfony2 Dumped - last route (1000 routes) | 978 | 0.0010521817 | +0.0010410656 | 9365% slower
Symfony2 - unknown route (1000 routes) | 989 | 0.0061380323 | +0.0061269163 | 55118% slower
Symfony2 - last route (1000 routes) | 999 | 0.0061715401 | +0.0061604240 | 55419% slower
Aura v2 - last route (1000 routes) | 982 | 0.1814854888 | +0.1814743727 | 1632542% slower
Aura v2 - unknown route (1000 routes) | 977 | 0.1871979547 | +0.1871868386 | 1683932% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 6 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
php-r3 - first route | 989 | 0.0000097053 | +0.0000000000 | baseline
Pux ext - first route | 985 | 0.0000209825 | +0.0000112773 | 116% slower
FastRoute - first route | 999 | 0.0000382496 | +0.0000285443 | 294% slower
Symfony2 Dumped - first route | 991 | 0.0000611150 | +0.0000514098 | 530% slower
Symfony2 - first route | 978 | 0.0002531449 | +0.0002434397 | 2508% slower
Aura v2 - first route | 985 | 0.0003834265 | +0.0003737212 | 3851% slower
