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

**Aura v2 - last route (1000 routes)** (999 runs): average time was 0.0098132791 seconds.
**Aura v2 - unknown route (1000 routes)** (974 runs): average time was 0.0094750439 seconds.
**FastRoute - last route (1000 routes)** (999 runs): average time was 0.0003970753 seconds.
**FastRoute - unknown route (1000 routes)** (973 runs): average time was 0.0003765039 seconds.
**Symfony2 - last route (1000 routes)** (999 runs): average time was 0.0066306352 seconds.
**Symfony2 - unknown route (1000 routes)** (991 runs): average time was 0.0084500950 seconds.
**Symfony2 Dumped - last route (1000 routes)** (987 runs): average time was 0.0019083359 seconds.
**Symfony2 Dumped - unknown route (1000 routes)** (988 runs): average time was 0.0020100472 seconds.
**Pux ext - last route (1000 routes)** (999 runs): average time was 0.0017104299 seconds.
**Pux ext - unknown route (1000 routes)** (998 runs): average time was 0.0011432293 seconds.


Results:

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
FastRoute - unknown route (1000 routes) | 0.0003765039 | +0.0000000000 | baseline
FastRoute - last route (1000 routes) | 0.0003970753 | +0.0000205714 | 5% slower
Pux ext - unknown route (1000 routes) | 0.0011432293 | +0.0007667254 | 204% slower
Pux ext - last route (1000 routes) | 0.0017104299 | +0.0013339261 | 354% slower
Symfony2 Dumped - last route (1000 routes) | 0.0019083359 | +0.0015318320 | 407% slower
Symfony2 Dumped - unknown route (1000 routes) | 0.0020100472 | +0.0016335433 | 434% slower
Symfony2 - last route (1000 routes) | 0.0066306352 | +0.0062541314 | 1661% slower
Symfony2 - unknown route (1000 routes) | 0.0084500950 | +0.0080735912 | 2144% slower
Aura v2 - unknown route (1000 routes) | 0.0094750439 | +0.0090985401 | 2417% slower
Aura v2 - last route (1000 routes) | 0.0098132791 | +0.0094367753 | 2506% slower


## First route matching
This benchmark tests how quickly each router can match the first route. 1,000 routes each with 9 arguments.

This benchmark consists of 5 tests. Each test is executed 1,000 times, the results pruned, and then averaged. Values that fall outside of 3 standard deviations of the mean are discarded.


**Aura v2 - first route** (995 runs): average time was 0.0003257167 seconds.
**FastRoute - first route** (983 runs): average time was 0.0000453121 seconds.
**Symfony2 - first route** (998 runs): average time was 0.0002373314 seconds.
**Symfony2 Dumped - first route** (994 runs): average time was 0.0000635095 seconds.
**Pux ext - first route** (984 runs): average time was 0.0000224775 seconds.


Results:

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
Pux ext - first route | 0.0000224775 | +0.0000000000 | baseline
FastRoute - first route | 0.0000453121 | +0.0000228346 | 102% slower
Symfony2 Dumped - first route | 0.0000635095 | +0.0000410320 | 183% slower
Symfony2 - first route | 0.0002373314 | +0.0002148539 | 956% slower
Aura v2 - first route | 0.0003257167 | +0.0003032392 | 1349% slower
