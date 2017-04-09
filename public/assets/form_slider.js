$(document).ready(function(){
	var current = 1;
	
	widget      = $(".step");
	btnnext     = $(".next");
	btnback     = $(".back"); 
	btnsubmit   = $(".submit");

	// INIT BUTTONS AND UI
	widget.not(':eq(0)').hide();
	hideButtons(current);
	setProgress(current);

	// NEXT BUTTON CLICK ACTION
	btnnext.click(function(){
		if(current < widget.length){
			// CHECK VALIDATION
			if($(".form").valid()){
				widget.show();
				widget.not(':eq('+(current++)+')').hide();
				setProgress(current);
			}
		}
		hideButtons(current);
	})

	// SUBMIT BUTTON CLICK
	//btnsubmit.click(function(){
	//	alert("Submit button clicked");
	//});

	// BACK BUTTON CLICK ACTION
	btnback.click(function(){
		if(current > 1){
			current = current - 2;
			if(current < widget.length){
				widget.show();
				widget.not(':eq('+(current++)+')').hide();
				setProgress(current);
			}
		}
		hideButtons(current);
	})

    $('.form').validate({ // INITIALIZE PLUGIN
		ignore:":not(:visible)",			
		rules: {
			name     : "required",
			email    : {required : true, email:true},
			country  : "required",
			username : "required",
			password : "required",
			rpassword: { required : true, equalTo: "#password"},
		},
    });
});
////////////////////////////////
// CHANGE PROGRESS BAR ACTION //
////////////////////////////////
setProgress = function(currstep){
	var percent = parseFloat(100 / widget.length) * currstep;
	percent = percent.toFixed();
	$(".progress-bar").css("width",percent+"%").html(percent+"%");		
}
////////////////////////////////////////////////
// HIDE BUTTONS ACCORDING TO THE CURRENT STEP //
////////////////////////////////////////////////
hideButtons = function(current){
	var limit = parseInt(widget.length); 

	$(".action").hide();

	if(current < limit) btnnext.show();
	if(current > 1) btnback.show();
	if (current == limit) { 
		// Show entered values
		$(".display label.lbl").each(function(){
			$(this).html($("#"+$(this).data("id")).val());	
		});
		btnnext.hide(); 
		btnsubmit.show();
	}
}