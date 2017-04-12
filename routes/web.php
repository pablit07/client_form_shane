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
	$camera_rtsp_port = Setting::firstOrCreate(['name' => 'camerartspport']);
	$camera_http_port = Setting::firstOrCreate(['name' => 'camerahttpport']);
	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);

	if (!$ip_address->value || !($camera_rtsp_port->value || $camera_http_port->value)) {
		Log::error("Error with camera settings; check settings.");
		return redirect('http://' . $_SERVER['HTTP_HOST'] . env('SITEURL') . '/public/error.html');
	}

	if ($camera_rtsp_port->value) {
		$protocol = 'rtsp://';
		$port = $camera_rtsp_port->value;
	} else if ($camera_http_port->value) {
		$protocol = 'http://';
		$port = $camera_http_port->value;
	}

	$cam_address = $protocol . $ip_address->value . ':' . $port . '/' . 'cgi-bin/view/image?pro_0&1491257463051';

	// $cmd = '/Users/paulkohlhoff/Projects/dice/dice ' . $cam_address . ' > /dev/null 2>&1 & echo $!; ';
	$cmd = 'xvfb-run /home/ubuntu/dice/dice.py "' . $cam_address . '" > /dev/null & echo $!;';

	Log::info('Running command: ' . $cmd);

	$camera_pid = Setting::firstOrCreate(['name' => 'camerapid']);

	if ($camera_pid->value) {
		exec('kill ' . $camera_pid->value);
		$camera_pid->value = null;
	}

	// $outputfile = fopen("/Users/paulkohlhoff/log.txt", "w");
	$outputfile = fopen("/home/ubuntu/log.txt", "w");
	$pid = exec($cmd, $outputfile);

	$camera_pid->value = $pid;
	$camera_pid->save();

	return $pid;
}

function stop_cam() {
	$camera_pid = Setting::firstOrCreate(['name' => 'camerapid']);
	$pid = $camera_pid->value;

	if ($pid) {
		exec('kill ' . $pid);
		$camera_pid->value = null;
		$camera_pid->save();
	}

	return $pid;
}

$app->get('startcam', function() {
	$pid = start_cam();

	return 'Success! pid=' . $pid;
});

$app->get('stopcam', function() {
	$pid = stop_cam();

	return 'Success! killed pid=' . $pid;
});

$app->get('ipaddress', function() {
	return response()->json(['ipaddress' => $_SERVER['REMOTE_ADDR']]);
});

$app->get('settings', function () {
	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);
	$spreadsheet_id = Setting::firstOrCreate(['name' => 'spreadsheetid']);
	$camera_user = Setting::firstOrCreate(['name' => 'camerauser']);
	$camera_pw = Setting::firstOrCreate(['name' => 'camerapw']);
	$camera_http_port = Setting::firstOrCreate(['name' => 'camerahttpport']);
	$camera_rtsp_port = Setting::firstOrCreate(['name' => 'camerartspport']);

    return response()->json(['ip_address' => $ip_address->value, 
    						 'camera_state' => $camera_state->value,
    						 'spreadsheet_id' => $spreadsheet_id->value,
    						 'camera_user' => $camera_user->value,
    						 'camera_pw' => $camera_pw->value,
    						 'camera_http_port' => $camera_http_port->value,
    						 'camera_rtsp_port' => $camera_rtsp_port->value
    						 ]);
});

$app->post('settings', function (Request $request) {
	$ip_address_value = $request->input('ip_address');
	$camera_state_value = $request->input('camera_state');
	$spreadsheet_id_value = $request->input('spreadsheet_id');
	$camera_user_value = $request->input('camera_user');
	$camera_pw_value = $request->input('camera_pw');
	$camera_http_port_value = $request->input('camera_http_port');
	$camera_rtsp_port_value = $request->input('camera_rtsp_port');

	$ip_address = Setting::firstOrCreate(['name' => 'ipaddress']);
	$camera_state = Setting::firstOrCreate(['name' => 'camerastate']);
	$spreadsheet_id = Setting::firstOrCreate(['name' => 'spreadsheetid']);
	$camera_user = Setting::firstOrCreate(['name' => 'camerauser']);
	$camera_pw = Setting::firstOrCreate(['name' => 'camerapw']);
	$camera_http_port = Setting::firstOrCreate(['name' => 'camerahttpport']);
	$camera_rtsp_port = Setting::firstOrCreate(['name' => 'camerartspport']);

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

	if ($camera_user_value) {
		$camera_user->value = $camera_user_value;
	}

	if ($camera_pw_value) {
		$camera_pw->value = $camera_pw_value;
	}

	if ($camera_http_port_value) {
		$camera_http_port->value = $camera_http_port_value;
	}

	if ($camera_rtsp_port_value) {
		$camera_rtsp_port->value = $camera_rtsp_port_value;
	}

	$ip_address->save();
	$camera_state->save();
	$spreadsheet_id->save();
	$camera_user->save();
	$camera_pw->save();
	$camera_http_port->save();
	$camera_rtsp_port->save();

    return response()->json(['ip_address' => $ip_address->value, 
    						 'camera_state' => $camera_state->value,
    						 'spreadsheet_id' => $spreadsheet_id->value,
     						 'camera_user' => $camera_user->value,
    						 'camera_pw' => $camera_pw->value,
    						 'camera_http_port' => $camera_http_port->value,
    						 'camera_rtsp_port' => $camera_rtsp_port->value
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
		Log::error($error);
		return redirect('http://' . $_SERVER['HTTP_HOST'] . env('SITEURL') . '/public/error.html');
	} else {
		$accessToken = $client->fetchAccessTokenWithAuthCode($code);
		if (isset($accessToken['error'])) {
			Log::error($accessToken['error']);
			return redirect('http://' . $_SERVER['HTTP_HOST'] . env('SITEURL') . '/public/error.html');
		} else {
			$spreadsheetCode = Setting::firstOrCreate(['name' => 'apitoken']);
			$spreadsheetCode->value = json_encode($accessToken);
			$spreadsheetCode->save();
		}
	}

	return response('', 200);
});

$app->get('spreadsheet/test', function() {
	try {
		$accessToken = json_decode(Setting::where(['name' => 'apitoken'])->first()->value, true);
		$spreadsheetId = Setting::where(['name' => 'spreadsheetid'])->first()->value;
	} catch(Exception $ex) {

	}
	$is_valid = false;
	$is_access_token_expired = true;
	if (isset($accessToken) && isset($spreadsheetId)) {
		try {
			$client = get_client();
			$client->setAccessToken($accessToken);
			$is_access_token_expired = $client->isAccessTokenExpired();
		} catch(Exception $ex) {
			Log::error($ex->getMessage());
		}
		if (!$is_access_token_expired) {
			try {
				$service = new Google_Service_Sheets($client);
				$is_valid = $service->spreadsheets_values->get($spreadsheetId, 'A1') !== null;
			} catch(Exception $ex) {
				Log::error($ex->getMessage());
			}
		}
	}
	return response()->json(['is_valid' => $is_valid, 'is_access_token_expired' => $is_access_token_expired]);
});

