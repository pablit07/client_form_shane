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

function start_cam() {
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
}

function stop_cam() {
	$camera_pid = Setting::firstOrCreate(['name' => 'camerapid']);
	$pid = $camera_pid->value;

	if ($pid) {
		exec('kill ' . $pid);
		$camera_pid->value = null;
		$camera_pid->save();
	}
}

$app->get('startcam', function() {
	start_cam();

	return 'Success! pid=' . $pid;
});

$app->get('stopcam', function() {
	stop_cam();

	return 'Success! killed pid=' . $pid;
});

$app->get('ipaddress', function() {
	return response()->json(['ipaddress' => $_SERVER['REMOTE_ADDR']]);
});

$app->get('settings', function () {
	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);
	$spreadsheet_id = Setting::firstOrCreate(['name' => 'spreadsheetid']);

    return response()->json(['ip_address' => $ip_address->value, 
    						 'camera_state' => $camera_state->value,
    						 'spreadsheet_id' => $spreadsheet_id->value
    						 ]);
});

$app->post('settings', function (Request $request) {
	$ip_address_value = $request->input('ip_address');
	$camera_state_value = $request->input('camera_state');
	$spreadsheet_id_value = $request->input('spreadsheet_id');

	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);
	$spreadsheet_id = Setting::firstOrCreate(['name' => 'spreadsheetid']);

	if ($ip_address_value) {
		$ip_address->value = $ip_address_value;
	}

	if ($camera_state_value !== null) {
		$camera_state->value = $camera_state_value;
		if ($camera_state_value === '1') {
			start_cam();
		} else {
			stop_cam();
		}
	}

	if ($spreadsheet_id_value) {
		$spreadsheet_id->value = $spreadsheet_id_value;
	}

	$ip_address->save();
	$camera_state->save();
	$spreadsheet_id->save();

    return response()->json(['ip_address' => $ip_address->value, 
    						 'camera_state' => $camera_state->value,
    						 'spreadsheet_id' => $spreadsheet_id->value
    						 ]);
});

define('APPLICATION_NAME', 'Dice');
define('CLIENT_SECRET_PATH', realpath(__DIR__ . '/..') . '/client_secret.json');
// If modifying these scopes, delete your previously saved credentials
// at ~/.credentials/sheets.googleapis.com-php-quickstart.json
define('SCOPES', implode(' ', array(
  Google_Service_Sheets::SPREADSHEETS)
));

function get_client() {
	$client = new Google_Client();
	$client->setApplicationName(APPLICATION_NAME);
	$client->setScopes(SCOPES);
	$client->setAuthConfig(CLIENT_SECRET_PATH);
	$client->setAccessType('offline');
	return $client;
}

$app->get('spreadsheet/auth', function() {
	$authUrl = null;
	$accessToken = null;

	$client = get_client();
	$client->setRedirectUri('http://' . $_SERVER['HTTP_HOST'] . env('SITEURL') . '/api/spreadsheet/authredirect');

	// Request authorization from the user.
	$authUrl = $client->createAuthUrl();

	return response()->json(['auth_url' => $authUrl, 'auth_token' => $accessToken]);
});

// http://ec2-34-209-18-233.us-west-2.compute.amazonaws.com/client/api/spreadsheet/authredirect/
$app->get('spreadsheet/authredirect', function(Request $request) {
	$code = $request->input('code');
	$error = $request->input('error');
	$client = get_client();

	if ($error) {
		Log::info($error);
	} else {
		$accessToken = $client->fetchAccessTokenWithAuthCode($code);
		if (isset($accessToken['error'])) {
			Log::info($accessToken['error']);
		} else {
			$spreadsheetCode = Setting::firstOrCreate(['name' => 'apitoken']);
			$spreadsheetCode->value = json_encode($accessToken);
			$spreadsheetCode->save();
		}
	}

	return response('', 200);
});

$app->get('spreadsheet/test', function() {
	$accessToken = json_decode(Setting::where(['name' => 'apitoken'])->first()->value, true);
	$spreadsheetId = Setting::where(['name' => 'spreadsheetid'])->first()->value;
	$client = get_client();
	$client->setAccessToken($accessToken);
	$service = new Google_Service_Sheets($client);
	$service->spreadsheets_values->get($spreadsheetId, 'A1');
	return response('', 200);
});

