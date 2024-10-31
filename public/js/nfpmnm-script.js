function nfpmnmCheckResolution(nfpmnmMaxWidth, nfpmnmMinWidth){

	let nfpmnmScreenWidth = jQuery(window).outerWidth();	

	//check srollbar width
	nfpmnmScrollbarWidth = (nfpmnmScreenWidth - jQuery(window).innerWidth());
	
	//if scrollbar width exceed the 10% of the screen width, recalculate the screen width
	if(nfpmnmScrollbarWidth > nfpmnmScreenWidth * 0.1){
		
		nfpmnmScreenWidth = nfpmnmScreenWidth - nfpmnmScrollbarWidth;
		
	}
																											
	if(nfpmnmScreenWidth >= nfpmnmMinWidth && nfpmnmScreenWidth < nfpmnmMaxWidth) {

		console.log("Screen resolution detected: "+nfpmnmScreenWidth);

		jQuery("body").addClass("nfpmnm-hide");
		
	} 	
										
}

function nfpmnmWarning(nfpmnmWarningText,nfpmnmDeviceList){
	
	if(jQuery("body").hasClass("nfpmnm-hide")){		
	
		jQuery("body > *").css("opacity","0");
		
		setTimeout(function(){
			
			if(!jQuery("body").hasClass("nfpmnm-alerted")){

				alert(nfpmnmWarningText+nfpmnmDeviceList);
				
				jQuery("body").addClass("nfpmnm-alerted");				
				
			}
			
		}, 250)
		
	} else {

		jQuery("body > *").css("opacity","1");
													
	}		

	jQuery("body").removeClass("nfpmnm-hide");								
	
}

let nfpmnmScreenWidth;