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

This benchmark consists of 12 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
FastRoute - unknown route (1000 routes) | 965 | 0.0003722053 | +0.0000000000 | baseline
FastRoute - last route (1000 routes) | 999 | 0.0003778025 | +0.0000055973 | 2% slower
Pux ext - unknown route (1000 routes) | 985 | 0.0012333790 | +0.0008611738 | 231% slower
Symfony2 Dumped - last route (1000 routes) | 983 | 0.0014034302 | +0.0010312250 | 277% slower
Symfony2 Dumped - unknown route (1000 routes) | 993 | 0.0016060610 | +0.0012338558 | 331% slower
Pux ext - last route (1000 routes) | 991 | 0.0019055481 | +0.0015333429 | 412% slower
Symfony2 - last route (1000 routes) | 999 | 0.0058133834 | +0.0054411781 | 1462% slower
Aura v2 - unknown route (1000 routes) | 970 | 0.0081023951 | +0.0077301899 | 2077% slower
Aura v2 - last route (1000 routes) | 998 | 0.0096134803 | +0.0092412750 | 2483% slower
Symfony2 - unknown route (1000 routes) | 994 | 0.0097540495 | +0.0093818442 | 2521% slower
Dash - unknown route (1000 routes) | 970 | 0.0510062439 | +0.0506340386 | 13604% slower
Dash - last route (1000 routes) | 999 | 0.0514250606 | +0.0510528553 | 13716% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 6 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
Pux ext - first route | 978 | 0.0000191886 | +0.0000000000 | baseline
FastRoute - first route | 988 | 0.0000250497 | +0.0000058611 | 31% slower
Symfony2 Dumped - first route | 978 | 0.0000518656 | +0.0000326770 | 170% slower
Aura v2 - first route | 999 | 0.0001707521 | +0.0001515635 | 790% slower
Symfony2 - first route | 977 | 0.0001870927 | +0.0001679041 | 875% slower
Dash - first route | 999 | 0.0541013915 | +0.0540822029 | 281846% slower