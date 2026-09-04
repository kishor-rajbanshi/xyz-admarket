<?php
date_default_timezone_set("UTC");
header('P3P:CP="IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT"');
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . date("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Content-type:application/javascript");

?>
(function () {

	if(typeof Set_Cookie == 'undefined')
	{		
		function Set_Cookie( name, value, expires, path, domain, secure ) 
		{	
			if(expires)
			expires = expires * 1000 * 60 * 60 ;
			
			var expires_date = new Date( today.getTime() + (expires) );
			document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
		}
	}
		

	var today = new Date();
    today.setTime( today.getTime() );
    today.setHours(today.getHours()+1);
    today.setMinutes(0);
    today.setSeconds(0);
    
  
    var ItemDataScript_src	= document.currentScript.src;
    if(!document.currentScript.src)    
    {
	    var jsObject			= document.getElementsByTagName("script");
	    var jsIndex				= jsObject.length-1;
	    var ItemDataScript		= jsObject[jsIndex];
	    var ItemDataScript_src	= ItemDataScript.src;    
    }
    
    ItemDataScript_parameter			= ItemDataScript_src.split("click.php?");
    ItemDataScript_parameter_new		= ItemDataScript_parameter[1];
    ItemDataScript_parameter_seperate	= ItemDataScript_parameter_new.split("&");
  
    adid			= ItemDataScript_parameter_seperate[0];
    page_referrer	= window.location.href;
  
    var param_array	= page_referrer.split("utm_campain=");
    
    if(param_array.length > 0)
    {
    	var param_array_split = param_array[1].split("&utm_source");
    
    	var param_list		  = param_array_split[0].split("-");
    	
    	param_aid     = param_list[0];
    	param_kid     = param_list[1];
    	param_aduid   = param_list[2];
    	param_sid     = param_list[3];
    	param_expiry  = param_list[4];
    	param_clickid = param_list[5];
    	param_data    = param_list[6];
    	
	   if(adid == param_aid && param_expiry > 0)
	   Set_Cookie('cpa_tracking_'+adid,param_array_split[0],(param_expiry * 24),"/") ;     
   }
   
})();