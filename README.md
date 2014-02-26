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

An example route: `/9b37eef21e/{arg1}/{arg2}/{arg3}/{arg4}/{arg5}/{arg6}/{arg7}/{arg8}/{arg9}/bda37e9f9b`

This is to simulate absolute worst case matching. Clearly, the routes used for this benchmark are not 
at all like what you'd see in a real application. For example, the Symfony2 router contains a prefix 
optimization that groups routes by the first letters of the route-- very useful for a real application.


### Last/unknown route (1000 routes)

#### Pux extension enabled 

This benchmark consists of 10 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
FastRoute - unknown route (1000 routes) | 0.0003877172 | +0.0000000000 | baseline
FastRoute - last route (1000 routes) | 0.0004231266 | +0.0000354094 | 9% slower
Pux PHP - unknown route (1000 routes) | 0.0009871890 | +0.0005994717 | 155% slower
Symfony2 Dumped - unknown route (1000 routes) | 0.0010901142 | +0.0007023970 | 181% slower
Pux PHP - last route (1000 routes) | 0.0011134467 | +0.0007257295 | 187% slower
Symfony2 Dumped - last route (1000 routes) | 0.0011774707 | +0.0007897535 | 204% slower
Symfony2 - unknown route (1000 routes) | 0.0058273004 | +0.0054395832 | 1403% slower
Symfony2 - last route (1000 routes) | 0.0062613664 | +0.0058736492 | 1515% slower
Aura v2 - last route (1000 routes) | 0.0093994704 | +0.0090117532 | 2324% slower
Aura v2 - unknown route (1000 routes) | 0.0094650060 | +0.0090772888 | 2341% slower


#### Pux extension disabled

This benchmark consists of 10 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
FastRoute - unknown route (1000 routes) | 0.0004740092 | +0.0000000000 | baseline
FastRoute - last route (1000 routes) | 0.0005117982 | +0.0000377890 | 8% slower
Symfony2 Dumped - unknown route (1000 routes) | 0.0010985712 | +0.0006245620 | 132% slower
Symfony2 Dumped - last route (1000 routes) | 0.0012421367 | +0.0007681275 | 162% slower
Pux PHP - unknown route (1000 routes) | 0.0029439456 | +0.0024699364 | 521% slower
Pux PHP - last route (1000 routes) | 0.0030395426 | +0.0025655334 | 541% slower
Symfony2 - unknown route (1000 routes) | 0.0061433836 | +0.0056693744 | 1196% slower
Symfony2 - last route (1000 routes) | 0.0063333072 | +0.0058592980 | 1236% slower
Aura v2 - unknown route (1000 routes) | 0.0100008997 | +0.0095268905 | 2010% slower
Aura v2 - last route (1000 routes) | 0.0102597146 | +0.0097857054 | 2064% slower

