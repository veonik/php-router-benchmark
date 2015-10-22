PHP Router Benchmark
====================

The intent here is to benchmark different PHP routing solutions. This is a micro-optimization, done purely out of 
dumb curiosity.


Installation
------------

Clone the repo, run `composer install`, run `php run-tests.php`.

You can install the [Pux](https://github.com/c9s/pux) extension to test that as well. If the extension is not
installed, the tests will fallback to the pure PHP implementation of Pux.

To test the [R3 library](https://github.com/c9s/php-r3), you also need to install that extension. If the extension is
not installed, the tests for R3 will be skipped.

Currently
---------

The current test creates 1000 routes, each with a randomized prefix and postfix, with 9 parameters each.

It was run with the [Pux](https://github.com/c9s/pux) and [R3](https://github.com/c9s/php-r3) extensions enabled.

An example route: `/9b37eef21e/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/bda37e9f9b`

## Worst-case matching
This benchmark matches the last route and unknown route. It generates a randomly prefixed and suffixed route in an attempt to thwart any optimization. 1,000 routes each with 9 arguments.

This benchmark consists of 14 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
r3 - unknown route (1000 routes) | 997 | 0.0000078341 | +0.0000000000 | baseline
r3 - last route (1000 routes) | 995 | 0.0000105369 | +0.0000027029 | 35% slower
Router - unknown route (1000 routes) | 992 | 0.0000188012 | +0.0000109671 | 140% slower
Router - last route (1000 routes) | 992 | 0.0000199661 | +0.0000121321 | 155% slower
FastRoute - unknown route (1000 routes) | 989 | 0.0003324273 | +0.0003245933 | 4143% slower
FastRoute - last route (1000 routes) | 998 | 0.0003505095 | +0.0003426754 | 4374% slower
Symfony2 Dumped - unknown route (1000 routes) | 998 | 0.0008737620 | +0.0008659280 | 11053% slower
Pux ext - unknown route (1000 routes) | 998 | 0.0009388064 | +0.0009309723 | 11884% slower
Pux ext - last route (1000 routes) | 998 | 0.0010594999 | +0.0010516658 | 13424% slower
Symfony2 Dumped - last route (1000 routes) | 998 | 0.0011358077 | +0.0011279736 | 14398% slower
Symfony2 - last route (1000 routes) | 995 | 0.0048178922 | +0.0048100581 | 61399% slower
Symfony2 - unknown route (1000 routes) | 994 | 0.0050545266 | +0.0050466926 | 64420% slower
Aura v2 - last route (1000 routes) | 986 | 0.1500148822 | +0.1500070481 | 1914802% slower
Aura v2 - unknown route (1000 routes) | 996 | 0.1623154067 | +0.1623075726 | 2071815% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 8 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
php-r3 - first route | 997 | 0.0000078204 | +0.0000000000 | baseline
Pux ext - first route | 999 | 0.0000160752 | +0.0000082548 | 106% slower
Router - unkown route | 994 | 0.0000186509 | +0.0000108304 | 138% slower
Router - first route | 992 | 0.0000213913 | +0.0000135709 | 174% slower
FastRoute - first route | 988 | 0.0000356728 | +0.0000278523 | 356% slower
Symfony2 Dumped - first route | 991 | 0.0000493382 | +0.0000415178 | 531% slower
Symfony2 - first route | 999 | 0.0001964001 | +0.0001885797 | 2411% slower
Aura v2 - first route | 998 | 0.0003710765 | +0.0003632561 | 4645% slower

