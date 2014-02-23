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

### Last/unknown route (1000 routes)

#### Pux extension enabled 

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
FastRoute - last route (1000 routes) | 0.0003850248 | +0.0000000000 | baseline
FastRoute - unknown route (1000 routes) | 0.0003861249 | +0.0000011000 | baseline
Symfony2 Dumped - unknown route (1000 routes) | 0.0010010412 | +0.0006160164 | 160% slower
Pux ext - last route (1000 routes) | 0.0010780096 | +0.0006929848 | 180% slower
Pux ext - unknown route (1000 routes) | 0.0010874066 | +0.0007023817 | 182% slower
Symfony2 Dumped - last route (1000 routes) | 0.0011106706 | +0.0007256457 | 188% slower
Symfony2 - unknown route (1000 routes) | 0.0052613673 | +0.0048763424 | 1267% slower
Symfony2 - last route (1000 routes) | 0.0055262056 | +0.0051411808 | 1335% slower
Aura v2 - last route (1000 routes) | 0.0089107320 | +0.0085257071 | 2214% slower
Aura v2 - unknown route (1000 routes) | 0.0089471850 | +0.0085621601 | 2224% slower

#### Pux extension disabled:

Test Name | Time | + Interval | Change
--------- | ---- | ---------- | ------
FastRoute - unknown route (1000 routes) | 0.0003805178 | +0.0000000000 | baseline
FastRoute - last route (1000 routes) | 0.0004208237 | +0.0000403059 | 11% slower
Symfony2 Dumped - last route (1000 routes) | 0.0011894119 | +0.0008088940 | 213% slower
Symfony2 Dumped - unknown route (1000 routes) | 0.0012693062 | +0.0008887884 | 234% slower
Pux PHP - unknown route (1000 routes) | 0.0025493205 | +0.0021688026 | 570% slower
Pux PHP - last route (1000 routes) | 0.0027761906 | +0.0023956728 | 630% slower
Symfony2 - last route (1000 routes) | 0.0058649409 | +0.0054844230 | 1441% slower
Symfony2 - unknown route (1000 routes) | 0.0062237343 | +0.0058432165 | 1536% slower
Aura v2 - last route (1000 routes) | 0.0089282593 | +0.0085477415 | 2246% slower
Aura v2 - unknown route (1000 routes) | 0.0091238108 | +0.0087432930 | 2298% slower

### First route

```bash
Running 3 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

Results:
Test Name                          	Time                	+ Interval          	Change
FastRoute - first route            	0.0000270230        	+0.0000000000       	baseline
Pux PHP - first route              	0.0000338626        	+0.0000068396       	25% slower
Symfony 2 - first route            	0.0001849046        	+0.0001578817       	584% slower
```
