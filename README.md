### Doubts / Findings

I have added aura router to 
[php-router-benchmark](https://github.com/tyler-sommer/php-router-benchmark)
and found aura is the slowest one.

    $ php run-tests.php 
    Running 8 tests, 1000 times each...
    The 100 highest and lowest results will be disregarded.

    For Aura v2 - last route (1000 routes), out of 800 runs, average time was: 0.0060516742 secs.
    For Aura v2 - unknown route (1000 routes), out of 800 runs, average time was: 0.0060279649 secs.
    For FastRoute - last route (1000 routes), out of 800 runs, average time was: 0.0002477944 secs.
    For FastRoute - unknown route (1000 routes), out of 800 runs, average time was: 0.0002375710 secs.
    For Symfony 2 - last route (1000 routes), out of 800 runs, average time was: 0.0031924027 secs.
    For Symfony 2 - unknown route (1000 routes), out of 800 runs, average time was: 0.0031264257 secs.
    For Pux PHP - last route (1000 routes), out of 800 runs, average time was: 0.0021123934 secs.
    For Pux PHP - unknown route (1000 routes), out of 800 runs, average time was: 0.0020294347 secs.


    Results:
    Test Name                          	Time                	+ Interval          	Change
    FastRoute - unknown route (1000 routes)	0.0002375710        	+0.0000000000       	baseline
    FastRoute - last route (1000 routes)	0.0002477944        	+0.0000102234       	4% slower
    Pux PHP - unknown route (1000 routes)	0.0020294347        	+0.0017918637       	754% slower
    Pux PHP - last route (1000 routes) 	0.0021123934        	+0.0018748224       	789% slower
    Symfony 2 - unknown route (1000 routes)	0.0031264257        	+0.0028888547       	1216% slower
    Symfony 2 - last route (1000 routes)	0.0031924027        	+0.0029548317       	1244% slower
    Aura v2 - unknown route (1000 routes)	0.0060279649        	+0.0057903939       	2437% slower
    Aura v2 - last route (1000 routes) 	0.0060516742        	+0.0058141032       	2447% slower


According to the [benchmark results in Pux](https://github.com/c9s/Pux/issues/17#issuecomment-32275754) 
[Aura.Router](https://github.com/auraphp/Aura.Router) was much faster 
than Symfony. That made me think to look into this benchmark.

Even if I have not installed Pux I was getting the results. 
So wondering how should I get the result. Doesn't I get an error?

So I added a script with [php timer](https://github.com/sebastianbergmann/php-timer/) 
to check the correctness of this.

The benchmark code is at simpetest.php .

One of the things I noticed is fastroute is fast itself.
When comparing on an average of ~20 ms with aura/router.

Here is the results of `simpletest.php` with 1000 routes having 10 parameters.

    Route found in fast route 
     Found fast route at 93 ms
     Not found fast route at 89 ms
    Route found in aura 
     From the controller inside aura 999
     Found aura route at 115 ms
     Route not found in 121 ms
    Route found in symfony
     From the controller inside symfony999
     Found symfony route at 321 ms
     Route not found symfony route at 321 ms
    
That said the @pmjones (the creator of aura and who is already an
expert in benchmarking) himself have said 

> I have to caution against reading too much into this. 
> The routing system is probably not a bottleneck for most apps, and 
> even the fastest routing system is not likely to have a huge effect 
> on the end-to-end performance of an app. But even so, 
> it's nice to have something to compare to. :-)

You can find [here](https://github.com/c9s/Pux/issues/17#issuecomment-32276937)

Some other questions to address is how the controller is assumed in FastRoute.
May be the assumption is handler is considered as the controller and 
you want to dispatch it.

In aura and in symfony we can do something like this 

    'controller' => function () {
        echo "Hello from controller";
    }
    
I am not sure how this could be done in FastRoute.

So to benchmark a system and to say which one performs better 
you need to know more on the functionalities the library can offer.

When running on a cli I am not sure whether the router assumes to be 
having the request method as `GET` . If so the result is 
correct for symfony and fast route. Else the result for the call is wrong.
The value of `$_SERVER['REQUEST_METHOD']` will not be set, so does `addGet`
method of aura doesnot find the route and only `add` method is capable of
finding the result.
