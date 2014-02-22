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

This is the PHP version of Pux versus FastRoute. 1000 routes, with 9 parameters each.

```bash
Running 2 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

For FastRoute - last route (1000 routes), out of 800 runs, average time was: 0.0004002538 secs.
For Pux PHP - last route (1000 routes), out of 800 runs, average time was: 0.0041987944 secs.


Results:
Test Name                          	    Time                	+ Interval          	Change
FastRoute - last route (1000 routes)	0.0004002538        	+0.0000000000       	baseline
Pux PHP - last route (1000 routes) 	    0.0041987944        	+0.0037985405       	949% slower
```