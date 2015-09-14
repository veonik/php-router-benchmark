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
r3 - last route (1000 routes) | 998 | 0.0000219957 | +0.0000000000 | baseline
r3 - unknown route (1000 routes) | 989 | 0.0000232766 | +0.0000012809 | 6% slower
FastRoute - unknown route (1000 routes) | 988 | 0.0008175699 | +0.0007955742 | 3617% slower
FastRoute - last route (1000 routes) | 988 | 0.0008405223 | +0.0008185266 | 3721% slower
Symfony2 Dumped - unknown route (1000 routes) | 983 | 0.0021163105 | +0.0020943148 | 9521% slower
Symfony2 Dumped - last route (1000 routes) | 990 | 0.0025903439 | +0.0025683483 | 11677% slower
Pux PHP - unknown route (1000 routes) | 981 | 0.0045674659 | +0.0045454702 | 20665% slower
Pux PHP - last route (1000 routes) | 992 | 0.0051111209 | +0.0050891252 | 23137% slower
Symfony2 - unknown route (1000 routes) | 973 | 0.0090566973 | +0.0090347016 | 41075% slower
Symfony2 - last route (1000 routes) | 998 | 0.0096361680 | +0.0096141723 | 43709% slower
Aura v2 - unknown route (1000 routes) | 980 | 0.3795325072 | +0.3795105116 | 1725387% slower
Aura v2 - last route (1000 routes) | 978 | 0.3948666711 | +0.3948446754 | 1795101% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 6 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


Test Name | Results | Time | + Interval | Change
--------- | ------- | ---- | ---------- | ------
php-r3 - first route | 987 | 0.0000159752 | +0.0000000000 | baseline
Pux PHP - first route | 984 | 0.0000486335 | +0.0000326583 | 204% slower
FastRoute - first route | 969 | 0.0000897977 | +0.0000738225 | 462% slower
Symfony2 Dumped - first route | 977 | 0.0001364784 | +0.0001205032 | 754% slower
Symfony2 - first route | 999 | 0.0006037644 | +0.0005877892 | 3679% slower
Aura v2 - first route | 979 | 0.0009121995 | +0.0008962242 | 5610% slower
