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

		if(typeof myEqualsIgnoreCase == 'undefined')
		{
			function myEqualsIgnoreCase(arg)
			{               
			        return (new String(this.toLowerCase())==(new String(arg)).toLowerCase());
			}
		}	
	
		String.prototype.equalsIgnoreCase=myEqualsIgnoreCase;
		String.prototype.equals=myEquals;   	
	
	
		if(typeof utf8_encode == 'undefined')
		{
			function utf8_encode(string) 
			{
				string = string.replace(/\r\n/g,"\n");
				var utftext = "";
	
				for (var n = 0; n < string.length; n++) 
				{
	
					var c = string.charCodeAt(n);
	
					if (c < 128) 
					{
						utftext += String.fromCharCode(c);
					}
					else if((c > 127) && (c < 2048)) 
					{
						utftext += String.fromCharCode((c >> 6) | 192);
						utftext += String.fromCharCode((c & 63) | 128);
					}
					else 
					{
						utftext += String.fromCharCode((c >> 12) | 224);
						utftext += String.fromCharCode(((c >> 6) & 63) | 128);
						utftext += String.fromCharCode((c & 63) | 128);
					}
	
				}
	
				return escape(utftext);
			}
		} 
		 
		if(typeof Set_Cookie_Data == 'undefined')
		{			
			function Set_Cookie_Data(name,value,expires,path,domain,secure) 
			{	
			    var Daytoday = new Date();
				
				var expires_date = new Date(Daytoday.getTime()+(expires));
				document.cookie = name + "=" +escape(value) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
			}		
		}
		
		if(typeof Get_Cookie == 'undefined')
		{			
			function Get_Cookie( name )
			{
				var start = document.cookie.indexOf( name + "=" );
				var len = start + name.length + 1;
				if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) )
				{
					return null;
				}
	
				if ( start == -1 ) return null;
				var end = document.cookie.indexOf( ";", len );
				if ( end == -1 ) end = document.cookie.length;
				return unescape( document.cookie.substring( len, end ) );
			}
		}

		if(typeof Set_Cookie == 'undefined')
		{		
			function Set_Cookie( name, value, expires, path, domain, secure ) 
			{	
				if ( expires )
				{
					expires = expires * 1000 * 60 * 60 ;
				}
				var expires_date = new Date( today.getTime() + (expires) );
				document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
			}
		}
		

		if(typeof myEquals == 'undefined')
		{		
			function myEquals(arg)
			{
			        return (this.toString()==arg.toString());
			}
		}

		
		
		
		
	    if(typeof GetOffsetData == 'undefined')
	    {
		    function GetOffsetData(object,offset)
		    {
		    	if(!object)
		        return;
		    	
		    	offset.x += object.offsetLeft;
		    	offset.y += object.offsetTop;
		    	
		    	GetOffsetData(object.offsetParent,offset)
		    }
	    }
	    
	    
	    if(typeof GetScrollData == 'undefined')
	    {
		    function GetScrollData(object,scroll)
		    {
		    	if(!object)
		    	return;
		    	
		    	scroll.x += object.scrollLeft;
		    	scroll.y += object.scrollTop;
		    	
		    	if(object.tagName.toLowerCase() != 'html')
		    	GetScrollData(object.parentNode,scroll);
		    }
	    }		
		
		
		
	if(typeof ResizeSkinAd == 'undefined')		
	{		
		 function ResizeSkinAd(container_width,container_id,adunit,left,right,top,bottom) 
		 {
		    var body_width 		 = document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
		    var container_top    = document.getElementById(container_id).offsetTop;
		    var container_left   = document.getElementById(container_id).offsetLeft;
		    var container_right  = container_width+container_left;
		    
		    var skin_left_width  = container_left-3;
		    var skin_right_width = body_width-container_right-3;
		    var skin_top_height  = container_top-3;
		
		
			if(left ==1)
			document.getElementById("xyz-SkinLeftContainer-"+adunit).style.width = skin_left_width+"px";
			
			if(right ==1)
			document.getElementById("xyz-SkinRightContainer-"+adunit).style.width = skin_right_width+"px";
			
			if(top ==1)
			{
			    if(skin_top_height >100)
			    document.getElementById("xyz-SkinTopContainer-"+adunit).style.height = skin_top_height+"px";
			}
			if(bottom ==1)
			{
			    //if(skin_top_height >100)
			    document.getElementById("xyz-SkinBottomContainer-"+adunit).style.height = "100px";
			}
		 }
	} 
	 
	 
	if(typeof SkinReplace == 'undefined')	 
	{
		function SkinReplace(background_images,skin_side_heights,adunit)
		{
		        var body_width = document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
		
		        var keys	= Object.keys(background_images);
		        var result;
		        var height;
		        
		        for(var i=0, l=keys.length; i < l; i++)
		        {
		         	if(keys[i] >= body_width)
		         	{
		         		result = keys[i];
		         		break;
		         	}
		         	else
		         	result = keys[i];		         	
		        }
		        
		        background_image=background_images[result];
		        height=skin_side_heights[result];

		
				if(document.getElementById("xyz-SkinLeftContainer-"+adunit))
		        document.getElementById("xyz-SkinLeftContainer-"+adunit).style.height = height+'px';
		        
		        if(document.getElementById("xyz-SkinRightContainer-"+adunit))
		        document.getElementById("xyz-SkinRightContainer-"+adunit).style.height = height+'px';
		
				if(document.getElementById('xyz-SkinImageContainer-'+adunit))
		        document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundImage="url('"+background_image+"')";
		}
	}		


		
   if(typeof HideExpandable == 'undefined')
   {
		function HideExpandable(aduid,expandable_type,expandable_style)
		{
			document.getElementById('img-close-container-'+aduid).style.display='none';



			if(expandable_style ==0)
			hide_currentwidth=0;

			
			if(expandable_style ==1)
			hide_currentheight=0;

			
			window['sizeintervalclose-'+aduid]= window.setInterval(function()
			{
				if(expandable_style ==0 || expandable_style ==2)	
				{
					currentheight=document.getElementById('xyz-expandable-container-'+aduid).style.height;
					currentheight=currentheight.replace('px');
							
					
					if(parseFloat(currentheight) >0)
					balanceheight=parseFloat(currentheight)-20;
					else
					balanceheight=0;
					
					
					hide_currentheight=0;
			
			
					if(parseFloat(balanceheight) >20)
					hide_currentheight=parseFloat(currentheight)-20;
					else if(parseFloat(balanceheight) >0)
					hide_currentheight=parseFloat(currentheight)-parseFloat(balanceheight);	
			
			
					if(hide_currentheight >= 0)
					document.getElementById('xyz-expandable-container-'+aduid).style.height=hide_currentheight+'px';
				}
			

				if(expandable_style ==1 || expandable_style ==2)
				{	
					currentwidth=document.getElementById('xyz-expandable-container-'+aduid).style.width;
					currentwidth=currentwidth.replace('px');
			
			
					if(parseFloat(currentwidth) >0)
					balancewidth=parseFloat(currentwidth)-20;
					else
					balancewidth=0;
					
					
					hide_currentwidth=0;		
			
					if(parseFloat(balancewidth) >20)
					hide_currentwidth=parseFloat(currentwidth)-20;
					else if(parseFloat(balancewidth) >0)
					hide_currentwidth=parseFloat(currentwidth)-parseFloat(balancewidth);	
					
					
				
					if(hide_currentwidth >= 0)
					document.getElementById('xyz-expandable-container-'+aduid).style.width=hide_currentwidth+'px';
			     }
			     

				window['expandable-open-'+aduid]=0;	

				if(hide_currentwidth === 0 && hide_currentheight === 0)
				{
					spancontainer = document.getElementById('xyz-expandable-container-'+aduid);
					
					offset = {x:0,y:0};
					scroll = {x:0,y:0};
					
					GetOffsetData(spancontainer,offset);
					GetScrollData(spancontainer.parentNode,scroll);
					
					
					posLeft = offset.x -scroll.x;
					posTop = offset.y -scroll.y;
					
	
					
					if(posLeft <= 728)
					{
						spancontainer.style.removeProperty("left");
						spancontainer.style.right='0px';	
					}
					else
					{
						spancontainer.style.removeProperty("right");
						spancontainer.style.left='0px';	
					}
				
					if(posTop <= 300)
					{
						spancontainer.style.top='0px';	
						spancontainer.style.removeProperty("bottom");	
						
						document.getElementById('img-close-container-'+aduid).style.removeProperty("top");
						document.getElementById('img-close-container-'+aduid).style.bottom='0px';					
					}
					else
					{
						spancontainer.style.removeProperty("top");
						spancontainer.style.bottom='0px';	
						
						document.getElementById('img-close-container-'+aduid).style.removeProperty("bottom");	
						document.getElementById('img-close-container-'+aduid).style.top='0px';	
					}

					window.clearInterval(window['sizeintervalclose-'+aduid]);
				}
				
			}, 1);
		}    
    }	


    
	
	if(typeof ShowExpandable == 'undefined')
	{
		function ShowExpandable(expandableresponsedata,aduid)
		{
			spancontainer = document.getElementById('xyz-expandable-container-'+aduid);
			
			offset = {x:0,y:0};
			scroll = {x:0,y:0};
			
			GetOffsetData(spancontainer,offset);
			GetScrollData(spancontainer.parentNode,scroll);
			
			
			posLeft = offset.x -scroll.x;
			posTop = offset.y -scroll.y;
			

			
			if(posLeft <= expandableresponsedata.width)
			{
				spancontainer.style.removeProperty("left");
				spancontainer.style.right='0px';	
			}
			else
			{
				spancontainer.style.removeProperty("right");
				spancontainer.style.left='0px';	
			}
			
			if(posTop <= expandableresponsedata.height)
			{
				spancontainer.style.top='0px';	
				spancontainer.style.removeProperty("bottom");	
				
				document.getElementById('img-close-container-'+aduid).style.removeProperty("top");
				document.getElementById('img-close-container-'+aduid).style.bottom='0px';					
			}
			else
			{
				spancontainer.style.removeProperty("top");
				spancontainer.style.bottom='0px';	
				
				document.getElementById('img-close-container-'+aduid).style.removeProperty("bottom");	
				document.getElementById('img-close-container-'+aduid).style.top='0px';	
			}
			
			var heightflag=0;
			var widthflag=0;
			
			
			ratioheight=expandableresponsedata.height;
			ratiowidth=expandableresponsedata.width;
	
	
			if(ratiowidth > 600 || ratioheight > 600)
			multiplay=4;
			else if(ratiowidth > 400 || ratioheight > 400)
			multiplay=3;
			else 
			multiplay=2;			
			
	
			if(ratiowidth > ratioheight)
	        {
	           addheight=multiplay;
	           addwidth=(ratiowidth/ratioheight)*multiplay;
	        }
	        else if(ratioheight > ratiowidth)
	        {
	           addwidth=multiplay;
	           addheight=(ratioheight/ratiowidth)*multiplay;
	        }
	        else
			{
	           addheight=multiplay;
	           addwidth=multiplay;
			}


			if(expandableresponsedata.style ==0)
			{
				document.getElementById('xyz-expandable-container-'+aduid).style.width=expandableresponsedata.width+'px';
				widthflag=1;
			}
			
			if(expandableresponsedata.style ==1)
			{
				document.getElementById('xyz-expandable-container-'+aduid).style.height=expandableresponsedata.height+'px';
				heightflag=1;
			}

				
			window['sizeintervalexpshow-'+aduid]=window.setInterval(function(){ 
					
					
			if(expandableresponsedata.style ==0 || expandableresponsedata.style ==2)	
			{	
				currentheight=document.getElementById('xyz-expandable-container-'+aduid).style.height;
				currentheight=currentheight.replace('px');
							
							
				balanceheight=expandableresponsedata.height-parseFloat(currentheight);
						
				if(balanceheight >addheight)
				currentheightplus=parseFloat(currentheight)+addheight;
				else
				currentheightplus=parseFloat(currentheight)+parseFloat(balanceheight);	
						
				if(currentheightplus <= expandableresponsedata.height && heightflag ==0)
				document.getElementById('xyz-expandable-container-'+aduid).style.height=currentheightplus+'px';
			
				if(currentheightplus == expandableresponsedata.height)
				heightflag=1;
			}
			
			
			if(expandableresponsedata.style ==1 || expandableresponsedata.style ==2)
			{
				currentwidth=document.getElementById('xyz-expandable-container-'+aduid).style.width;
				currentwidth=currentwidth.replace('px');
					
				balancewidth=expandableresponsedata.width-parseFloat(currentwidth);
					
				if(balancewidth >addwidth)
				currentwidthplus=parseFloat(currentwidth)+addwidth;
				else
				currentwidthplus=parseFloat(currentwidth)+parseFloat(balancewidth);	
					
		
				if(currentwidthplus <= expandableresponsedata.width && widthflag ==0)
				document.getElementById('xyz-expandable-container-'+aduid).style.width=currentwidthplus+'px';
		
				if(currentwidthplus == expandableresponsedata.width)
				widthflag=1;
			}		
			
					
			if(widthflag ==1 && heightflag ==1)
			{
				window.clearInterval(window['sizeintervalexpshow-'+aduid]);
				
				document.getElementById('img-close-container-'+aduid).style.display="block";	
				
				currentwidthplus=0;
				currentheightplus=0;
				
			}
				
			}, 1);
		}
	}
	
	
	
	if(typeof LoadExpandable == 'undefined')
	{
	    function LoadExpandable(expandableresponsedata,aduid)
	    {
	    	divouterelement=document.getElementById('div-outer-'+aduid);
	    	
	    	
	    	divouterelement.style.position="relative";

	    
			spanelement = document.createElement("SPAN"); 
			spanelement.setAttribute('id','xyz-expandable-container-'+aduid);
			spanelement.setAttribute('style','position:absolute;overflow:hidden;width:0px;height:0px;z-index: 100000000000;');
			
			divouterelement.insertBefore(spanelement,divouterelement.childNodes[0]);
			
			

			spanelement_parent=document.getElementById('xyz-expandable-container-'+aduid);
			

		
	    	string_span="<a target='_blank' id='anchor-expandable-container-"+aduid+"' href='' >";
	    	string_span+="<img id='img-expandable-container-"+aduid+"' src='' />";
	    	string_span+="</a>";
	    	string_span+="<span class='img-close-box' id='img-close-container-"+aduid+"' style='position: absolute;right:0px;cursor:pointer;display:none;color: #FFFFFF;background-color: #CCCCCC;padding: 0px 5px;'>x</span>";
	    	
	    
	        spanelement_parent.innerHTML=string_span;
	    	
	    
	    	if(document.getElementById('img-expandable-container-'+aduid).src == window.location.href)
	    	{
				document.getElementById('img-expandable-container-'+aduid).style.width=expandableresponsedata.width+'px';
				document.getElementById('img-expandable-container-'+aduid).style.height=expandableresponsedata.height+'px';
				document.getElementById('img-expandable-container-'+aduid).src=base64_decode(expandableresponsedata.bannerpath);
	    	}
	    	
			document.getElementById('img-expandable-container-'+aduid).style.display="block";
				
			document.getElementById('anchor-expandable-container-'+aduid).href=expandableresponsedata.clksurl;
	    }
    }

 
	

    if(typeof base64_encode == 'undefined')
    {		
		function base64_encode(data) 
		{
			  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
			    ac = 0,
			    enc = "",
			    tmp_arr = [];

			  if (!data) {
			    return data;
			  }

			  do { // pack three octets into four hexets
			    o1 = data.charCodeAt(i++);
			    o2 = data.charCodeAt(i++);
			    o3 = data.charCodeAt(i++);

			    bits = o1 << 16 | o2 << 8 | o3;

			    h1 = bits >> 18 & 0x3f;
			    h2 = bits >> 12 & 0x3f;
			    h3 = bits >> 6 & 0x3f;
			    h4 = bits & 0x3f;

			    // use hexets to index into b64, and append result to encoded string
			    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4);
			  } while (i < data.length);

			  enc = tmp_arr.join('');
			  var r = data.length % 3;

			  ret=(r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3);
			  ret=ret.replace('+',',');
			  ret=ret.replace('/','-');
			  
			  return ret;
		}	
	}
	
	
   if(typeof base64_decode == 'undefined')
    {		
		function base64_decode(data) 
		{
			  var b64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
			  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
			    ac = 0,
			    dec = "",
			    tmp_arr = [];
			
			  if (!data) {
			    return data;
			  }
			
			  data += '';
			
			  do { // unpack four hexets into three octets using index points in b64
			    h1 = b64.indexOf(data.charAt(i++));
			    h2 = b64.indexOf(data.charAt(i++));
			    h3 = b64.indexOf(data.charAt(i++));
			    h4 = b64.indexOf(data.charAt(i++));
			
			    bits = h1 << 18 | h2 << 12 | h3 << 6 | h4;
			
			    o1 = bits >> 16 & 0xff;
			    o2 = bits >> 8 & 0xff;
			    o3 = bits & 0xff;
			
			    if (h3 == 64) {
			      tmp_arr[ac++] = String.fromCharCode(o1);
			    } else if (h4 == 64) {
			      tmp_arr[ac++] = String.fromCharCode(o1, o2);
			    } else {
			      tmp_arr[ac++] = String.fromCharCode(o1, o2, o3);
			    }
			  } while (i < data.length);
			
			  dec = tmp_arr.join('');
			
			return dec;		
		}	
	}


    var today = new Date();
    today.setTime( today.getTime() );
    today.setHours(today.getHours()+1);
    today.setMinutes(0);
    today.setSeconds(0);




	this.post_noads_handler = function(aduid,urldata,e) 
	{ 
		var response 	= e.data;
		
		urldata=urldata.replace("https://","");
		urldata=urldata.replace("http://","");
		urldata=urldata.replace("www.","");

		urlorigin=e.origin;

		urlorigin=urlorigin.replace("https://","");
		urlorigin=urlorigin.replace("http://","");
		urlorigin=urlorigin.replace("www.","");


		var urlindex	= urldata.indexOf(urlorigin);		
		
		if(urlindex ==0)
		{			
			try
			{		
			responsedata=JSON.parse(response);		
			
				if(responsedata !="")
				{
	                if(responsedata.operation =='noads' && responsedata.auid == aduid)            
					{
						if(document.getElementById('adm-id-'+aduid))
						document.getElementById('adm-id-'+aduid).remove();
				             	
						if(document.getElementById('div-outer-'+aduid))
						document.getElementById('div-outer-'+aduid).remove();	
						
						if(document.getElementById("adm-container-"+aduid))
	            		document.getElementById("adm-container-"+aduid).remove();
					}				
				}	
			} catch (e) {}
		}		
     }



		this.post_skin_handler = function(aduid,urldata,e) 
		{ 
			http_urldata=urldata;
		
			var response 	= e.data;
			
			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");
	
			urlorigin=e.origin;
	
			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");
	
	
			var urlindex	= urldata.indexOf(urlorigin);			

			
			if(urlindex ==0)
			{
				try 
	            {
					responsedata=JSON.parse(response);		
	
	                if(responsedata !="")
	                {
	                    if(responsedata.operation =='skin_display' && responsedata.auid == aduid)
	                    {
							var body_width = document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
							
	                        var container_id    = responsedata.container_id;
                            var container_width = document.getElementById(container_id).offsetWidth;
                            

               				if(parseInt(body_width) >= parseInt(container_width))
	                    	{
	                             var iFrameID=document.getElementById('adm-id-'+aduid);
	                             var myObj = {"adcode":aduid, "message":'skin_success'};
	                             var myJSON = JSON.stringify(myObj);
	                             
	                             
	                             iFrameID.contentWindow.postMessage(myJSON,http_urldata);
	                             

	                             /* skin image render */
                                 var background_images=responsedata.skin_banners;
                                 var skin_side_heights=responsedata.skin_height;
                                 
                                 
                                 

                                 document.body.style.backgroundColor = "#E9E9E9";
                                 document.body.style.overflowX = "hidden";
                                 document.body.style.backgroundPosition = "center";
                                 document.body.style.backgroundSize='100% 100%';
                                 document.body.style.backgroundRepeat = "no-repeat";
                                 /* end  skin image render */
                                 




                                /**** left div *******/
		                        var s_l_w =responsedata.s_l_w; 
		                        var s_l_h =responsedata.s_s_h;
		                        var SkinLeftContainerId ='xyz-SkinLeftContainer-'+aduid;
		                        var SkinLeftContainer=document.createElement('div');
		                        SkinLeftContainer.id=SkinLeftContainerId;
		                        SkinLeftContainer.style.maxHeight=s_l_h+'px';
		                        SkinLeftContainer.style.maxWidth=s_l_w+'px';
		                        SkinLeftContainer.style.margin="0 auto";
		                        SkinLeftContainer.style.zIndex=1000000001;
		                        SkinLeftContainer.style.position='absolute';
		                        SkinLeftContainer.style.top="10px";
		                        SkinLeftContainer.style.left='0px';
		                        SkinLeftContainer.style.backgroundcolor='red';
		                        SkinLeftContainer.style.cursor='auto';
		
		
		
		
		
		                                /***** end left div ****/
		                                /**** right div *******/
		                        var s_r_w =responsedata.s_r_w; 
		                        var s_r_h =responsedata.s_s_h;
		                        var SkinRightContainerId ='xyz-SkinRightContainer-'+aduid;
		                        var SkinRightContainer=document.createElement('div');
		                        SkinRightContainer.id=SkinRightContainerId;
		                        SkinRightContainer.style.maxHeight=s_r_h+'px';
		                        SkinRightContainer.style.maxWidth=s_r_w+'px';
		                        SkinRightContainer.style.margin="0 auto";
		                        SkinRightContainer.style.zIndex=1000000001;
		                        SkinRightContainer.style.position='absolute';
		                        SkinRightContainer.style.top="10px";
		                        SkinRightContainer.style.right='0px';
		                        SkinRightContainer.style.backgroundcolor='green';
		                        SkinRightContainer.style.cursor='auto';
		
		                                /***** end right div ****/
		                                
		                         /**** top div *******/
		                        var s_t_w =100; 
		                        var s_t_h =responsedata.s_t_h;
		                        var SkinTopContainerId ='xyz-SkinTopContainer-'+aduid;
		                        var SkinTopContainer=document.createElement('div');
		                        SkinTopContainer.id=SkinTopContainerId;
		                        SkinTopContainer.style.maxHeight=s_t_h+'px';
		                        SkinTopContainer.style.width=s_t_w+'%';
		                        SkinTopContainer.style.margin="0 auto";
		                        SkinTopContainer.style.zIndex=1000000001;
		                        SkinTopContainer.style.position='absolute';
		                        SkinTopContainer.style.top="0px";
		                        SkinTopContainer.style.left='0px';
		                        SkinTopContainer.style.backgroundcolor='blue';
		                        SkinTopContainer.style.cursor='auto';
		                        
		                                /***** end top div ****/
		                        /**** bottom div *******/
		                        var s_b_w =100; 
		                        var s_b_h =responsedata.s_b_h;
		                        var SkinBottomContainerId ='xyz-SkinBottomContainer-'+aduid;
		                        var SkinBottomContainer=document.createElement('div');
		                        SkinBottomContainer.id=SkinBottomContainerId;
		                        SkinBottomContainer.style.maxHeight=s_b_h+'px';
		                        SkinBottomContainer.style.width=s_b_w+'%';
		                        SkinBottomContainer.style.margin="0 auto";
		                        SkinBottomContainer.style.zIndex=1000000001;
		                        SkinBottomContainer.style.position='absolute';
		                        SkinBottomContainer.style.bottom="0px";
		                        SkinBottomContainer.style.left='0px';
		                        SkinBottomContainer.style.backgroundcolor='black';
		                        SkinBottomContainer.style.cursor='auto';
		           
		                        /***** end bottom div ****/
		
		                        /* click url */
		                        var XyzSkinClickUrl=responsedata.click_url;
		
		                        var SkinClick_L_Id ='xyz-SkinClickLID-'+aduid;
		                        var SkinClickL=document.createElement('a');
		                        SkinClickL.id=SkinClick_L_Id;
		                        SkinClickL.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickL.style.height='100%';
		                        SkinClickL.style.width=s_l_w+'px';
		                        SkinClickL.style.zIndex=1000000001;
		                        SkinClickL.style.display="block";
		                        SkinClickL.target="_blank";
		                        SkinLeftContainer.appendChild(SkinClickL);
		
		                        var SkinClick_R_Id ='xyz-SkinClickRID-'+aduid;
		                        var SkinClickR=document.createElement('a');
		                        SkinClickR.id=SkinClick_R_Id;
		                        SkinClickR.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickR.style.height='100%';
		                        SkinClickR.style.width=s_r_w+'px';
		                        SkinClickR.style.zIndex=1000000001;
		                        SkinClickR.style.display="block";
		                        SkinClickR.target="_blank";
		                        SkinRightContainer.appendChild(SkinClickR);
		
		                        var SkinClick_T_Id ='xyz-SkinClickTID-'+aduid;
		                        var SkinClickT=document.createElement('a');
		                        SkinClickT.id=SkinClick_T_Id;
		                        SkinClickT.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickT.style.height=s_t_h+'px';
		                        SkinClickT.style.width='100%';
		                        SkinClickT.style.zIndex=1000000001;
		                        SkinClickT.style.display="block";
		                        SkinClickT.target="_blank";
		                        SkinTopContainer.appendChild(SkinClickT);
		
		                        var SkinClick_B_Id ='xyz-SkinClickBID-'+aduid;
		                        var SkinClickB=document.createElement('a');
		                        SkinClickB.id=SkinClick_B_Id;
		                        SkinClickB.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickB.style.height=s_b_h+'px';		                        
		                        SkinClickB.style.width='100%';
		                        SkinClickB.style.zIndex=1000000001;
		                        SkinClickB.style.display="block";
		                        SkinClickB.target="_blank";
		                        SkinBottomContainer.appendChild(SkinClickB);
		                        /* end click url */
		
		                        /* main div */
		                        var XyzSkinPositions=responsedata.skin_positions;
		
		                        var SkinMarketContainerId ='xyz-SkinMarketContainer-'+aduid;
		                        var SkinMarketContainer=document.createElement('div');
		                        SkinMarketContainer.id=SkinMarketContainerId;
		                        SkinMarketContainer.style.position= 'fixed';
		                        SkinMarketContainer.style.top= '0px';
		                        SkinMarketContainer.style.left= '0px';
		                        SkinMarketContainer.style.width= '100%';
		                        SkinMarketContainer.style.height= '';
		
		
		                        /* test for div height */
		                        var SkinImageContainerId ='xyz-SkinImageContainer-'+aduid;
		                        var SkinImageContainer=document.createElement('div');
		                        SkinImageContainer.id=SkinImageContainerId;
		                        SkinImageContainer.style.height= '100%';
		                        SkinImageContainer.style.backgroundPosition= 'center';
		                        SkinImageContainer.style.backgroundRepeat= 'no-repeat';
		                        SkinImageContainer.style.backgroundSize= 'cover';
		                        SkinImageContainer.style.position= 'fixed';
		                        SkinImageContainer.style.top= '0px';
		                        SkinImageContainer.style.left= '0px';
		                        SkinImageContainer.style.width= '100%';
		                        SkinImageContainer.style.zIndex='-1';
		                        SkinImageContainer.style.backgroundPosition ='';
		                        document.body.appendChild(SkinImageContainer);
		                        /*  end */
		                        
		                        if(XyzSkinPositions.L ==1)
		                        SkinMarketContainer.appendChild(SkinLeftContainer);
		
		                        if(XyzSkinPositions.R ==1)
		                        SkinMarketContainer.appendChild(SkinRightContainer);
		
		                        if(XyzSkinPositions.T ==1)
		                        SkinMarketContainer.appendChild(SkinTopContainer);
		
		                        if(XyzSkinPositions.B ==1)
		                        SkinMarketContainer.appendChild(SkinBottomContainer);
		
		                        document.body.appendChild(SkinMarketContainer);
		                        
		                        ResizeSkinAd(container_width,container_id,aduid,XyzSkinPositions.L,XyzSkinPositions.R,XyzSkinPositions.T,XyzSkinPositions.B);
		                        SkinReplace(background_images,skin_side_heights,aduid);
		                        
		                        window.addEventListener("resize", function() 
		                        {
		                        	ResizeSkinAd(container_width,container_id,aduid,XyzSkinPositions.L,XyzSkinPositions.R,XyzSkinPositions.T,XyzSkinPositions.B);
		                        	SkinReplace(background_images,skin_side_heights,aduid);
		                        });
                    		}       
	                	}      
	            	}
              	} catch (e) {}
			}
        }



		this.post_expandable_handler = function(aduid,urldata,e) 
		{ 
			var response 	= e.data;
			
			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");
	
			urlorigin=e.origin;
	
			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");
	
	
			var urlindex	= urldata.indexOf(urlorigin);			
			

			if(urlindex ==0)
			{	
				try
				{	
				responsedata=JSON.parse(response);		
				

				
				if(responsedata !="" && responsedata.auid == aduid)
				{
					if(responsedata.operation =='expandable_configuration' && responsedata.expandable ==1)
					{
						window['expandable-open-'+responsedata.auid]=0;
						
						LoadExpandable(responsedata,aduid);
					}


					
					if(responsedata.operation =='expandable_open' && responsedata.expandable ==2)
					{	
					
					
					
					
					 	window['expandableinterval1-'+responsedata.auid]=window.setInterval(function()
						{
							//if(window['expandable-open-'+responsedata.auid] ==0)
							{
								window['expandable-open-'+responsedata.auid]=1;	
							
								ShowExpandable(responsedata,aduid);
								
								if(document.getElementById("img-close-container-"+aduid))
								document.getElementById("img-close-container-"+aduid).onclick = function(){ HideExpandable(aduid,responsedata.expandable_type,responsedata.style); };								
							}
	
							
							window.clearInterval(window['expandableinterval1-'+responsedata.auid]);
						}, 100);	
					}
				}	
				} catch (e) {}	
			}		
        }







		this.post_pop_handler = function(aduid,urldata,e) 
		{ 
			var response 	= e.data;
			
			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");
	
			urlorigin=e.origin;
	
			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");
	
	
			var urlindex	= urldata.indexOf(urlorigin);			
			
			if(urlindex ==0)
			{			
				try
				{		
				responsedata=JSON.parse(response);		
				
				if(responsedata !="")
				{
	                if(responsedata.operation =='popopen' && responsedata.auid == aduid)            
					{
						exptime=responsedata.addelay*1000*60; // For Converting Into Milliseconds
											
						if(exptime >0)
						Set_Cookie_Data('pop_delay_'+responsedata.auid,1,exptime,"/") ;
					}				
				}	
				} catch (e) {}	
			}		
        }


		this.post_interstitial_handler = function(aduid,urldata,e) 
		{ 
			var response	= e.data;
			
			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");
	
			urlorigin=e.origin;
	
			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");
	
	
			var urlindex	= urldata.indexOf(urlorigin);			
			
			
			if(urlindex ==0)
			{
				try
				{
				responsedata=JSON.parse(response);		
				
				if(responsedata !="")
				{
					if(responsedata.operation =='close' && responsedata.auid == aduid)
					{
						var rmelement = document.getElementById('xyz-interstitial-container-'+responsedata.auid);
						
						if(rmelement != null)
						{
							rmelement.parentNode.removeChild(rmelement);
							
							var rmelement1 = document.getElementById('xyz-interstitial-background-'+responsedata.auid);
							
							if(rmelement1 != null)
							rmelement1.parentNode.removeChild(rmelement1);
						}
						
						
						exptime=responsedata.addelay*1000*60; // For Converting Into Milliseconds
						
						if(exptime >0)
						Set_Cookie_Data('int_view_'+responsedata.auid,1,exptime,"/") ;
					}
					else if(responsedata.operation =='open' && responsedata.auid == aduid)
					{
					
					
					
						document.getElementById('xyz-interstitial-background-'+responsedata.auid).style.display="block";
						document.getElementById('xyz-interstitial-container-'+responsedata.auid).style.display="block";
						
						
						var ie=document.all && !window.opera;
						var iebody=(document.compatMode=="CSS1Compat")? document.documentElement : document.body ;
	
						ht=(ie)? iebody.clientHeight: window.innerHeight ;
						wt=(ie)? iebody.clientWidth : window.innerWidth ;
	
						ofht=document.getElementById('xyz-interstitial-container-'+responsedata.auid).offsetHeight;
						ofwt=document.getElementById('xyz-interstitial-container-'+responsedata.auid).offsetWidth;
	
						ofht=parseFloat(ofht)-20;
	
						document.getElementById('xyz-interstitial-container-'+responsedata.auid).style.top=(ht/2)-parseFloat(ofht/2) +'px';
						document.getElementById('xyz-interstitial-container-'+responsedata.auid).style.left=(wt/2)-parseFloat(ofwt/2) +'px';
					}
				}
				} catch (e) {}	
			}			
        }

		this.post_native_handler = function(aduid,urldata,e) 
		{ 
			var response	= e.data;
			
			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");
	
			urlorigin=e.origin;
	
			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");
	
			var urlindex	= urldata.indexOf(urlorigin);			

			if(urlindex ==0)
			{				
				try
				{			
				responsedata=JSON.parse(response);		
				
				if(responsedata !="")
				{
					if(responsedata.operation =='nativedata' && responsedata.auid == aduid)			
					{
						var iFrameID = document.getElementById('adm-id-'+responsedata.auid);
									   
						if(iFrameID)
						{
							iFrameID.style.height  = responsedata.height+'px' ;
							
							if(responsedata.responsive ==0)
							iFrameID.style.width   = responsedata.width+'px' ;
							else
							iFrameID.style.width   = '100%';
						}
					}
				}
				} catch (e) {}	
			}
        }
    
    
	   this.native_resize_handler = function resize_adunit(aduid) 
	   {
			var iFrameID = document.getElementById('adm-id-'+aduid);
	    	iFrameID.contentWindow.postMessage(aduid,"*");
	   }






    var async		= 0;  
    var native 		= 0;
    var dataparam 	= 0;
    var popcode		= 0;
    
 
    if(document.currentScript.async)
    var async=1;      
    
  
    var ItemDataScript_src=document.currentScript.src;
    if(!document.currentScript.src)    
    {
	    var jsObject=document.getElementsByTagName("script");
	    var jsIndex=jsObject.length-1;
	    var ItemDataScript=jsObject[jsIndex];
	    var ItemDataScript_src=ItemDataScript.src;    
    }
    

    var ItemDataScript_split=ItemDataScript_src.split("/items.php");

    if(ItemDataScript_split.length ==1)
    var ItemDataScript_split=ItemDataScript_src.split("/js/");   
    
    var ItemDataScript_dir=ItemDataScript_split[0]+'/index.php?page=query/items';

    
    ItemDataScript_parameter=ItemDataScript_split[1].split("?");
    ItemDataScript_parameter_new=ItemDataScript_parameter[1];
    
    ItemDataScript_parameter_seperate=ItemDataScript_parameter_new.split("&");
  
    aduid=ItemDataScript_parameter_seperate[0];
    pid=ItemDataScript_parameter_seperate[1];
    width=ItemDataScript_parameter_seperate[2];
    height=ItemDataScript_parameter_seperate[3];
    
    if(ItemDataScript_parameter_seperate.length >4)
    displaytype=ItemDataScript_parameter_seperate[4];
    else
    displaytype=0;    
    
    if(ItemDataScript_parameter_seperate.length >5)
	dataparam = ItemDataScript_parameter_seperate[5];
	

		
	if(dataparam == 9)
	popcode	= 1
	else if(dataparam == 1)
	native = 1;
	
	
	
	

	
	if(native ==1)
	width='100%';


	     	     
         if(displaytype ==14)
         {
             height =0;
             width  =0;
         }	     
	     
	     
			
		 if(window.top)
		 {
			if(window.top.location)
			{
				if(!window.top.location.hostname)
				return;
			}
			else
			return;
		 }
		 else
		 return;
			
			
			page_meta_data=document.getElementsByTagName('meta');
			page_title = document.title; 
			page_referrer=window.location.href;
			
				
			meta_description=""; 
			meta_keywords=""; 
			
			for(i=0; i< page_meta_data.length;i++)
			{
				if(page_meta_data[i].name.equalsIgnoreCase ('title') && (page_title.equalsIgnoreCase ("Untitled Document") || page_title==""))
				page_title=page_meta_data[i].content;
			
				if(page_meta_data[i].name.equalsIgnoreCase ('keywords'))
				meta_keywords=page_meta_data[i].content;
			
				if(page_meta_data[i].name.equalsIgnoreCase ('description'))
				meta_description=page_meta_data[i].content;
			}
			
			
			meta_keywords=meta_keywords.substr(0,400);
			search_keywords=meta_keywords;
			page_title=page_title.substr(0,200);
			meta_description=meta_description.substr(0,300);

			
			currently_rendered=0;
			currently_rendered_flag=0;
			if(window.currently_rendered_adunit)
			var currently_rendered_ids=window.currently_rendered_adunit;
			
			if(currently_rendered_ids)
			{
				var currently_rendered_idsarr=currently_rendered_ids.split(",");
				for(var i=0; i < currently_rendered_idsarr.length;i++)
				{
					if(currently_rendered_idsarr[i] == aduid)
					{
						currently_rendered=1;
						currently_rendered_flag=1;
						break;
					}
				}
				
				if(currently_rendered==0)
				currently_rendered_ids=currently_rendered_ids+aduid+',';
			}
			else
			currently_rendered_ids=aduid+',';

		
			window.currently_rendered_adunit=currently_rendered_ids;

			if(currently_rendered_flag==1)
			return;
			

<?php 
	function get_user_ip()
	{
		$getip="";
		
		if(isset($_SERVER))
		{
			if(isset($_SERVER['HTTP_CLIENT_IP']) && check_lan_ip($_SERVER['HTTP_CLIENT_IP']))
			$getip= $_SERVER['HTTP_CLIENT_IP'];
			else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && check_lan_ip($_SERVER['HTTP_X_FORWARDED_FOR']))
			$getip= $_SERVER['HTTP_X_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_X_FORWARDED']) && check_lan_ip($_SERVER['HTTP_X_FORWARDED']))
			$getip= $_SERVER['HTTP_X_FORWARDED'];
			else if(isset($_SERVER['HTTP_FORWARDED_FOR']) && check_lan_ip($_SERVER['HTTP_FORWARDED_FOR']))
			$getip= $_SERVER['HTTP_FORWARDED_FOR'];
			else if(isset($_SERVER['HTTP_FORWARDED']) && check_lan_ip($_SERVER['HTTP_FORWARDED']))
			$getip= $_SERVER['HTTP_FORWARDED'];
			else
			$getip= $_SERVER['REMOTE_ADDR'];
		}
		
		if(check_lan_ip(getenv('HTTP_CLIENT_IP')))
		$getip= getenv('HTTP_CLIENT_IP');
		else if(check_lan_ip(getenv('HTTP_X_FORWARDED_FOR')))
		$getip= getenv('HTTP_X_FORWARDED_FOR');
		else if(check_lan_ip(getenv('HTTP_X_FORWARDED')))
		$getip= getenv('HTTP_X_FORWARDED');
		else if(check_lan_ip(getenv('HTTP_FORWARDED_FOR')))
		$getip= getenv('HTTP_FORWARDED_FOR');
		else if(check_lan_ip(getenv('HTTP_FORWARDED')))
		$getip= getenv('HTTP_FORWARDED');
		else
		$getip= getenv('REMOTE_ADDR');
		
		if($getip !="")
		{
			$getip_array=explode(',',$getip);

			return $getip_array[count($getip_array) -1];
		}
		else
		return $getip;		
	}

	function check_lan_ip($ip)
	{
		if (!isset($ip) || $ip =='' || $ip ==0 || ($ip >= '127.0.0.0' && $ip <= '127.255.255.255') || ($ip >= '10.0.0.0' && $ip <= '10.255.255.255') || ($ip >= '172.16.0.0' && $ip <= '172.31.255.255') || ($ip >= '192.168.0.0' && $ip <= '192.168.255.255') || ($ip >= '169.254.0.0' && $ip <= '169.254.255.255'))
		return false;
		
		return $ip;
	}

	$time=time();
?>

			
			var url=ItemDataScript_dir;           
			url=url+"/&aduid="+aduid;	
			url=url+"&width="+width;	
			url=url+"&height="+height;	
			url=url+"&displaytype="+displaytype;	
            url=url+"&native="+native;	
            url=url+"&popcode="+popcode;	

            url=url+"&page_data=<?php echo md5(get_user_ip()."ADM-DATA-ADM".$_SERVER['HTTP_USER_AGENT'].($time+40)); ?>";
            url=url+"&time=<?php echo $time; ?>";	            
            
            url=url+"&deliver="+utf8_encode(window.location.hostname.replace('www.',''));
			url=url+"&search_keywords="+utf8_encode(search_keywords);
			url=url+"&page_referrer="+base64_encode(page_referrer);
			url=url+"&page_title="+utf8_encode(page_title);
			url=url+"&meta_description="+utf8_encode(meta_description);	
			

			iframe_src="";	


			var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
			var eventer = window[eventMethod];
					
			var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
			var resizeEvent  = eventMethod == "attachEvent" ? "onresize" : "resize";


			if(popcode == 1)
			{
				var pop_view=Get_Cookie('pop_delay_'+aduid);
				
				
				if(pop_view !=1)
				{
					eventer(messageEvent, this.post_pop_handler.bind(null,this.aduid,window.location.hostname),false);  

			    	iframe_src='<script data-cfasync="false" type="text/javascript" src="'+url+'"></script>';     
				}
			}			
			else
			{
				if(native==1)
				iframe_src="<iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' scrolling='no' style='width:100%;'></iframe>";
				else
				{
					if(displaytype ==5)
					iframe_src+="<iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:"+height+"px;width:"+width+"px;' scrolling='no' ></iframe>";
				    else
				    {
				    	if(displaytype ==14)
				        iframe_src+="<iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:0px;width:0px;position:relative;' scrolling='no'></iframe>";
				    	else
						iframe_src+="<div id='div-outer-"+aduid+"' style='height:"+height+"px;width:"+width+"px;'><iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:"+height+"px;width:"+width+"px;' scrolling='no' ></iframe></div>";
					}
				}
			}
			
			
		if(displaytype ==5)
		{
			var interstitial_view=Get_Cookie('int_view_'+aduid);
					
			if(interstitial_view !=1)
			{
				var interstitial_container_id='xyz-interstitial-container-'+aduid;
				var interstitial_background_id='xyz-interstitial-background-'+aduid;
						
				if(!document.getElementById(interstitial_container_id))
				{
					var interstitialContainer=document.createElement('div');
						interstitialContainer.id=interstitial_container_id;
						interstitialContainer.style.height=height+'px';
						interstitialContainer.style.width=width+'px';
						interstitialContainer.style.position="fixed";
						interstitialContainer.style.zIndex=1000000001;
						interstitialContainer.style.display="none";
							
						
						document.body.appendChild(interstitialContainer);
						
						document.getElementById(interstitial_container_id).innerHTML=iframe_src;
						
						var interstitialBackGround=document.createElement('div');
						interstitialBackGround.id=interstitial_background_id;
						interstitialBackGround.style.height='100%';
						interstitialBackGround.style.width='100%';
						interstitialBackGround.style.zIndex=1000000000;
						interstitialBackGround.style.position="fixed";
						interstitialBackGround.style.top="0px";					
						interstitialBackGround.style.background='#CCCCCC';
						interstitialBackGround.style.opacity=0.7;
						interstitialBackGround.style.display="none";
						
						document.body.appendChild(interstitialBackGround);
						
						eventer(messageEvent, this.post_interstitial_handler.bind(null,this.aduid,url),false);  
				}
			}
		}
		else
		{
			if(async==1 && popcode == 1)
			{
				if(pop_view !=1)
				{
					var s = document.createElement("script");
					s.setAttribute("data-cfasync","false");
					s.type = "text/javascript";
					s.src = url;
					document.getElementsByTagName('body')[0].appendChild(s);
				}
			}
            else if(async==1 && popcode !=1)
            {
            	if(document.getElementById("adm-container-"+aduid))
            	{
            		//////////////////// For ad display center ////////////////////
            		//document.getElementById("adm-container-"+aduid).style.width  = width+"px";
            		//document.getElementById("adm-container-"+aduid).style.height = height+"px";
            		//document.getElementById("adm-container-"+aduid).style.margin = "0px auto";
            		//////////////////// For ad display center ////////////////////
            		
            	
            		document.getElementById("adm-container-"+aduid).innerHTML=iframe_src;
            	}
            	else
           		document.getElementById('data_'+aduid).innerHTML=iframe_src;        // New version 'data_aduid' changed to 'adm-container-aduid'
           	}
       		else
            {            
	            document.open();
	            document.write(iframe_src);
	            document.close();
			}			
		
			if(displaytype !=5)
			eventer(messageEvent, this.post_expandable_handler.bind(null,this.aduid,url),false);  	// For set click url & expandable banner				

		
			if(native ==1)
			{
				eventer(messageEvent, this.post_native_handler.bind(null,this.aduid,url),false);  
						
				eventer(resizeEvent, this.native_resize_handler.bind(null,this.aduid) , false);  
			}
			
 			if(displaytype ==14)
		 	eventer(messageEvent, this.post_skin_handler.bind(null,this.aduid,url),false);  		
		}	
				
		if(popcode !=1)
		eventer(messageEvent, this.post_noads_handler.bind(null,this.aduid,url),false);  
			

	return;

	   
})();