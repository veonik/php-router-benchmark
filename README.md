nice benchmark
==============

The intent here is to benchmark different routing solutions.

This is a micro-optimization, done purely out of dumb curiosity.


Installation
------------

Clone the repo, run `composer install`, run `php run-tests.php`.

You can install the Pux extension to test that as well. See Pux docs for more info.


Currently
---------

### Last/unknown route (1000 routes)

The first test was done using the C extension version of Pux. 1000 routes, with 9 parameters each.

```bash
Running 6 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

Results:
Test Name                          	    Time                	+ Interval          	Change
FastRoute - unknown route (1000 routes)	0.0003282449        	+0.0000000000       	baseline
FastRoute - last route (1000 routes)	0.0003490779        	+0.0000208330       	6% slower
Pux ext - last route (1000 routes) 	    0.0008355537        	+0.0005073088       	155% slower
Pux ext - unknown route (1000 routes)	0.0008565038        	+0.0005282590       	161% slower
Symfony 2 - unknown route (1000 routes)	0.0049886054        	+0.0046603605       	1420% slower
Symfony 2 - last route (1000 routes)	0.0054083353        	+0.0050800905       	1548% slower
```

And the same test again, with the Pux extension disabled:

```bash
Running 6 tests, 1000 times each...
The 100 highest and lowest results will be disregarded.

Results:
Test Name                          	Time                	+ Interval          	Change
FastRoute - unknown route (1000 routes)	0.0003558156        	+0.0000000000       	baseline
FastRoute - last route (1000 routes)	0.0004698372        	+0.0001140216       	32% slower
Pux PHP - last route (1000 routes) 	0.0027003917        	+0.0023445761       	659% slower
Pux PHP - unknown route (1000 routes)	0.0027126130        	+0.0023567975       	662% slower
Symfony 2 - unknown route (1000 routes)	0.0051302084        	+0.0047743928       	1342% slower
Symfony 2 - last route (1000 routes)	0.0052936333        	+0.0049378178       	1388% slower
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
