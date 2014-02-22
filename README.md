nice benchmark
==============

The intent here is to benchmark different routing solutions.

This is a micro-optimization, done purely out of dumb curiosity.


Installation
------------

Clone the repo, run `composer install`, run `php test.php`.

You can install the Pux extension to test that as well. See Pux docs for more info.


Currently
---------

### Last route (1000 routes)

This is the PHP version of Pux versus FastRoute. 1000 routes, with 9 parameters each.

```bash
Running 3 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

For FastRoute - last route (1000 routes), out of 800 runs, average time was: 0.0003571352 secs.
For Pux PHP - last route (1000 routes), out of 800 runs, average time was: 0.0029948521 secs.
For Symfony 2 - last route (1000 routes), out of 800 runs, average time was: 0.0080974451 secs.


Results:
Test Name                          	    Time                	+ Interval          	Change
FastRoute - last route (1000 routes)	0.0003571352        	+0.0000000000       	baseline
Pux PHP - last route (1000 routes) 	    0.0029948521        	+0.0026377168       	739% slower
Symfony 2 - last route (1000 routes)	0.0080974451        	+0.0077403098       	2167% slower
```

### First route

```bash
Running 3 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

For FastRoute - first route, out of 800 runs, average time was: 0.0000270230 secs.
For Pux PHP - first route, out of 800 runs, average time was: 0.0000338626 secs.
For Symfony 2 - first route, out of 800 runs, average time was: 0.0001849046 secs.


Results:
Test Name                          	Time                	+ Interval          	Change
FastRoute - first route            	0.0000270230        	+0.0000000000       	baseline
Pux PHP - first route              	0.0000338626        	+0.0000068396       	25% slower
Symfony 2 - first route            	0.0001849046        	+0.0001578817       	584% slower
```
