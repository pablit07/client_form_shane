<!DOCTYPE html>
<html>
<head>
	<title>Dice Roll Recorder</title>
</head>
<body>

<?php 
  if ($_SERVER['PHP_AUTH_USER'] != 'thecrapscoach' || $_SERVER['PHP_AUTH_PW'] != 'crapscoach') {
    header('WWW-Authenticate: Basic realm="Dice Roll Recorder"');
    header('HTTP/1.0 401 Unauthorized');
    exit;
  }
?>

<!-- FONT AWESOME -->
<script src="https://use.fontawesome.com/370aaf74a2.js"></script>

<!-- BOOTSTRAP -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="assets/forms.css">

<!-- JQUERY -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<!-- FORM VALIDATION -->
<script type="text/javascript" src="assets/jquery.validate.js"></script>

<!-- VIEW MODEL BINDING -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/knockout/3.4.2/knockout-min.js"></script>

<style type="text/css">
header.navbar {
  padding-left: 10px;
  background-color: #FFFFCC;
}
.back, .next, .submit {
    display: inline-block;
  	width: 100% !important;  
  	height: 45px !important;
  	margin: 7px 0px 0px 0px !important;
  	padding: 0px 0px 0px 0px !important; 
  	font-size: 16px !important;
  	font-weight: normal !important;
  	text-align: center !important;
  	text-transform: uppercase !important;
  	font-family: "FontAwesome", 'Open Sans' !important;
  	vertical-align: middle !important;  
  	border: 0px solid transparent !important;
  	border-radius: 5px;
    -ms-border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -o-border-radius: 5px;
    color: #ffffff; /* TEXT COLOR */
    box-shadow: 0px 0px 0px transparent;
    webkit-box-shadow: 0px 0px 0px transparent;
    -moz-box-shadow: 0px 0px 0px transparent;
}
.back { /* BOOTSTRAP GREEN: #5CB85C / hover: #449D44, ZILLOW GREEN: HEX #74C005 / hover: #6CB403; RGBA (116,192,5,1) */  
    background-color: #5CB85C;  
}
.back:hover {
    background-color: #449D44;
}
.next, .submit { 
    background-color: #046392;
    background-image: linear-gradient(#2B7CA4, #046392);
    background-image: -moz-linear-gradient(#2B7CA4, #046392);
    background-image: -ms-linear-gradient(#2B7CA4, #046392);
    background-image: -o-linear-gradient(#2B7CA4, #046392);
    background-image: -webkit-linear-gradient(#2B7CA4, #046392);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#2B7CA4', endColorstr='#046392') !important;
}
.next:hover, .submit:hover {
    background-color: #1591D0;
    background-image: linear-gradient(#49B3E8, #1591D0);
    background-image: -moz-linear-gradient(#49B3E8, #1591D0);
    background-image: -ms-linear-gradient(#49B3E8, #1591D0);
    background-image: -o-linear-gradient(#49B3E8, #1591D0);
    background-image: -webkit-linear-gradient(#49B3E8, #1591D0);
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#49B3E8', endColorstr='#1591D0') !important;
}
#btn-authorize-google-drive {
  top: -30px;
  position: relative;
}
</style>
<header class="navbar navbar-light">
  <h2 class="viewport_title">
    <i class="fa fa-braille"></i> Craps Roll Recorder <i class="fa fa-braille"></i>
  </h2>
  <div class="pull-right">
      <button id="btn-authorize-google-drive" data-bind='click: authorize, text: (needsGoogleAuth() ? "Authorize Google Drive" : "Google Drive Authorized"), enable: needsGoogleAuth()'></button>
  </div>
</header>
<div class="container">

<form id="sellerform" class="form form_wrap_light" data-toggle="validator" role="form" name="seller_form" method="post" action="/home-value-api/" autocomplete="off">
<!-- class="inbound-now-form wpl-track-me inbound-track"-->


  <div class="step">
    <h3>Camera Network IP Address</h3>    
    <a href="javascript:void(0)" data-bind='click: useMyIp'>Use my IP</a>  
    <div class="row gutter-10">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="input-group">                
            <div class="input-group-addon"><span class="fa fa-video-camera"></span></div>

            <input type="text" class="form-control" id="ip-address" name="ipAddress" required data-required="true" maxlength="32" data-bind='value: ipAddress'/>            
                                        
          </div>
        </div>
      </div>
    </div>
  </div>


   <div class="step">
    <h3>Google Sheet ID</h3>        
    <div class="row gutter-10">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="input-group">   
            <div class="input-group-addon"><span class="fa fa-google"></span></div>

            <input type="text" class="form-control" id="spreadsheet-id" name="spreadsheetId" required data-required="true" data-bind='value: spreadsheetId'/>       
          </div>
        </div>
      </div>
    </div>
  </div>


   <div class="step">
    <h3>Camera Connection</h3>        
    <div class="row gutter-10">
      <div class="col-lg-12">

        <ul class="client_type">          
          <li>
            <input id='camera-state-on' type="radio" value="1" name="cameraState" data-bind="checked:cameraState, checkedValue: '1'"/>
            <label for="camera-state-on">On</label>
          </li>
          <li>
            <input id='camera-state-off' type="radio" value="0" name="cameraState" data-bind="checked:cameraState, checkedValue: '0'"/>
            <label for="camera-state-off">Off</label>
          </li>
        </ul>

      </div>
    </div>
  </div>


<!-- BUTTONS -->

  <div class="row gutter-10">
    
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      
      <button type="button" class="action back" data-bind='click: load'><i class="fa fa-angle-double-left"></i>&nbsp; Reset</button>

    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

      <button type="button" class="action submit" id="submit" name="submit" data-bind='click: save'>Save &nbsp;<i class="fa fa-angle-double-right"></i></button>

      </div>

  </div>

</form>
</div>
<br><br>
<div class="container">
  <div class="col-md-12">
    <h4>Camera Test</h4>
    <ul class="nav nav-pills">
      <li data-bind="css: {active: isCameraTestOn()}, click: cameraTest(true)"><a href="javascript:void(0)">On</a></li>
      <li data-bind="css: {active: !isCameraTestOn()}, click: cameraTest(false)"><a href="javascript:void(0)">Off</a></li>
    </ul>
  </div>

  <div class="col-md-6" style="margin-top: 20px">
    <iframe data-bind="attr: {src: testCameraSrc()}" width="420" height="246"></iframe>
  </div>
</div>

<script type="text/javascript">
  var SettingsViewModel = function() {
    var self = this;

    this.ipAddress = ko.observable('');
    this.spreadsheetId = ko.observable('');
    this.cameraState = ko.observable('0');
    this.needsGoogleAuth = ko.observable(true);
    this.isCameraTestOn = ko.observable(false);

    this.testCameraSrc = ko.computed(function () {
      console.log(self.isCameraTestOn());
      return self.isCameraTestOn() ? ('http://admin:admin@'+self.ipAddress()+'/video.htm') : '';
    });

    this.load = function(setInitialState) {
      $.get('../api/settings', function(data) {
        self.ipAddress(data.ip_address);
        self.cameraState(data.camera_state);
        self.spreadsheetId(data.spreadsheet_id);
      });

      $.get('../api/spreadsheet/test', function(data) {
        if (!data.is_valid) {
          alert('Error reading from spreadsheet!');
        }
        self.needsGoogleAuth(data.is_access_token_expired);
      });
    }

    this.load(true);

    this.save = function() {
      $.post('../api/settings', {
        'ip_address': self.ipAddress(),
        'camera_state': self.cameraState(),
        'spreadsheet_id': self.spreadsheetId()
      }).done(function() { alert('Saved!') });
    }

    this.authorize = function() {
      $.get('../api/spreadsheet/auth', function(data) {
        console.info(data);
        if (data.auth_url) {
          var authWindow = window.open(data.auth_url);
          var redirectCheck = setInterval(function() {
            if (!authWindow) {
              clearInterval(redirectCheck);
            }
            if (authWindow.location.href.indexOf('authredirect') !== -1) {
              clearInterval(redirectCheck);
              authWindow.close();
            }
          }, 500);
        }
      });
    }

    this.useMyIp = function() {
      $.get('../api/ipaddress', function(data) {
        if (data.ipaddress) {
          self.ipAddress(data.ipaddress);
        }
      });
    }

    this.cameraTest = function(state) {
      self.isCameraTestOn(state);
    }

  };

  $(function() {
    ko.applyBindings(new SettingsViewModel());
  });
</script>
</body>
</html>