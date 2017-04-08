
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

<style type="text/css">
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
</style>

<form id="sellerform" class="form form_wrap_light" data-toggle="validator" role="form" name="seller_form" method="post" action="/home-value-api/" autocomplete="off">
<!-- class="inbound-now-form wpl-track-me inbound-track"-->

<h2 class="viewport_title"><i class="fa fa-braille"></i> Craps Roll Recorder</h2>


  <div class="step">
    <h3>Camera Network IP Address</h3>      
    <div class="row gutter-10">
      <div class="col-lg-12">
        <div class="form-group">
          <div class="input-group">                
            <div class="input-group-addon"><span class="fa fa-video-camera"></span></div>

            <input type="text" class="form-control" id="first_name" name="first_name" data-map-form-field="wpleads_first_name" required data-required="true" maxlength="32"/>            
                                        
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
            <input type="radio" value="BUYER" name="amount" checked="checked" />
            <label for="BUYER">On</label>
          </li>
          <li>
            <input type="radio" value="SELLER" name="amount" />
            <label for="SELLER">Off</label>
          </li>
        </ul>

      </div>
    </div>
  </div>


<!-- BUTTONS -->

  <div class="row gutter-10">
    
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      
      <button type="button" class="action back"><i class="fa fa-angle-double-left"></i>&nbsp; Reset</button>

    </div>

    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">

      <button type="submit" class="action submit" id="submit" name="zillow_submit">Save &nbsp;<i class="fa fa-angle-double-right"></i></button>

      </div>

  </div>

</form>