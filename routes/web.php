<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('startcam', function() {
	$cmd = '/Users/paulkohlhoff/Projects/dice/dice camera > /dev/null 2>&1 & echo $!; ';
	// $pidArr = fopen("/Users/paulkohlhoff/pid.txt", "w");
	$outputfile = fopen("/Users/paulkohlhoff/log.txt", "w");
	$pid = exec($cmd, $outputfile);
	// exec(sprintf("%s > %s 2>&1 & echo $!", $cmd, $outputfile),$pidArr);
	return 'Success! ' . $pid;
});