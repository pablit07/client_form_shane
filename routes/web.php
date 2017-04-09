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
use App\Setting;
use Illuminate\Http\Request;


$app->get('/', function () use ($app) {
    return $app->version();
});

// $app->get('user/auth', ['middleware' => 'auth.basic', function () {
// 	$base = config('SITEURL');
//     return redirect($base . '/public/');
// }]);

$app->get('startcam', function() {
	$cmd = '/Users/paulkohlhoff/Projects/dice/dice camera > /dev/null 2>&1 & echo $!; ';
	$camera_pid = Setting::firstOrCreate(['name' => 'camerapid']);

	if ($camera_pid->value) {
		exec('kill ' . $camera_pid->value);
		$camera_pid->value = null;
	}

	$outputfile = fopen("/Users/paulkohlhoff/log.txt", "w");
	$pid = exec($cmd, $outputfile);

	$camera_pid->value = $pid;
	$camera_pid->save();

	return 'Success! pid=' . $pid;
});

$app->get('stopcam', function() {
	$camera_pid = Setting::firstOrCreate(['name' => 'camerapid']);
	$pid = $camera_pid->value;

	if ($pid) {
		exec('kill ' . $pid);
		$camera_pid->value = null;
		$camera_pid->save();
	}

	return 'Success! killed pid=' . $pid;
});

$app->get('ipaddress', function() {
	return response()->json(['ipaddress' => $_SERVER['REMOTE_ADDR']]);
});

$app->get('settings', function () {
	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);
    return response()->json(['ip_address' => $ip_address, 
    						 'camera_state' => $camera_state
    						 ]);
});

$app->post('settings', function (Request $request) {
	$ip_address_value = $request->input('ip_address');
	$camera_state_value = $request->input('camera_state');

	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);

	if ($ip_address_value) {
		$ip_address->value = $ip_address_value;
	}

	if ($camera_state_value) {
		$camera_state->value = $camera_state_value;
	}

	$ip_address->save();
	$camera_state->save();

    return response()->json(['ip_address' => $ip_address, 
    						 'camera_state' => $camera_state
    						 ]);
});