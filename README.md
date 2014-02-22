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

### Last/unknown route (1000 routes)

The first test was done using the C extension version of Pux versus FastRoute. 1000 routes, with 9 parameters each.

```bash
Running 6 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

Results:
Test Name                          	    Time                	+ Interval          	Change
FastRoute - unknown route (1000 routes)	0.0003509843        	+0.0000000000       	baseline
FastRoute - last route (1000 routes)	0.0003616071        	+0.0000106227       	3% slower
Pux ext - unknown route (1000 routes)	0.0008050108        	+0.0004540265       	129% slower
Pux ext - last route (1000 routes) 	    0.0008263820        	+0.0004753977       	135% slower
Symfony 2 - unknown route (1000 routes)	0.0050797191        	+0.0047287348       	1347% slower
Symfony 2 - last route (1000 routes)	0.0052590027        	+0.0049080184       	1398% slower
```

And the same test again, with the Pux extension disabled:

```bash
Running 6 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

Results:
Test Name                          	    Time                	+ Interval          	Change
FastRoute - unknown route (1000 routes)	0.0003568077        	+0.0000000000       	baseline
FastRoute - last route (1000 routes)	0.0003697601        	+0.0000129524       	4% slower
Pux PHP - unknown route (1000 routes)	0.0026281527        	+0.0022713450       	637% slower
Pux PHP - last route (1000 routes) 	    0.0026472646        	+0.0022904569       	642% slower
Symfony 2 - unknown route (1000 routes)	0.0049424902        	+0.0045856824       	1285% slower
Symfony 2 - last route (1000 routes)	0.0052162355        	+0.0048594278       	1362% slower
```


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
