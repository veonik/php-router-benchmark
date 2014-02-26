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

It was run with the Pux extension enabled.

An example route: `/9b37eef21e/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/bda37e9f9b`

## Worst-case matching
This benchmark matches the last route and unknown route. It generates a randomly prefixed and suffixed route in an attempt to thwart any optimization. 1,000 routes each with 9 arguments.

This benchmark consists of 10 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.

Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
FastRoute - last route (1000 routes) | 999 | 0.0005987507 | +0.0000000000 | baseline
FastRoute - unknown route (1000 routes) | 974 | 0.0007073336 | +0.0001085829 | 18% slower
Symfony2 Dumped - unknown route (1000 routes) | 978 | 0.0010984190 | +0.0004996683 | 83% slower
Pux ext - last route (1000 routes) | 999 | 0.0011521092 | +0.0005533585 | 92% slower
Pux ext - unknown route (1000 routes) | 997 | 0.0011798443 | +0.0005810937 | 97% slower
Symfony2 Dumped - last route (1000 routes) | 977 | 0.0012447905 | +0.0006460398 | 108% slower
Symfony2 - unknown route (1000 routes) | 977 | 0.0065360450 | +0.0059372943 | 992% slower
Symfony2 - last route (1000 routes) | 999 | 0.0075226317 | +0.0069238810 | 1156% slower
Aura v2 - unknown route (1000 routes) | 966 | 0.0094022778 | +0.0088035271 | 1470% slower
Aura v2 - last route (1000 routes) | 999 | 0.0102424956 | +0.0096437449 | 1611% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 5 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.

Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
Pux ext - first route | 979 | 0.0000192035 | +0.0000000000 | baseline
FastRoute - first route | 991 | 0.0000276991 | +0.0000084956 | 44% slower
Symfony2 Dumped - first route | 974 | 0.0000543768 | +0.0000351733 | 183% slower
Aura v2 - first route | 986 | 0.0001890168 | +0.0001698133 | 884% slower
Symfony2 - first route | 999 | 0.0002314818 | +0.0002122782 | 1105% slower
