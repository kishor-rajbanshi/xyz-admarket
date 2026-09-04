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

    if(adcode_count === undefined)
    var adcode_count = 1;
    else
    adcode_count++;


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

		if(typeof Set_Track_Cookie == 'undefined')
		{
			function Set_Track_Cookie(name, value, expires, path, domain, secure)
			{
				if(expires)
				expires = expires * 1000 * 60 * 60;

				var expires_date = new Date();

				if(expires >0)
				expires_date.setTime(expires_date.getTime()+expires);
				else
				expires_date.setHours(expires_date.getHours()+1);

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


	if(typeof SkinReplace == 'undefined')
	{
		function SkinReplace(background_images,skin_side_heights,adunit,responsedata)
		{
		        var body_width = document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
		        var body_height = document.documentElement.clientHeight || document.body.clientHeight || document.body.offsetHeight;


	            			var container_id    	= responsedata.container_id;
                        	var container_height	= document.getElementById(container_id).offsetHeight;


							var dataoffset = document.getElementById(container_id).getBoundingClientRect();

							var container_left_width		= dataoffset.left;
							var container_right_width		= body_width - dataoffset.right;




							var container_left_height		= body_height;
							var container_right_height		= body_height;



							var container_top_width			= body_width;
							var container_bottom_width		= body_width;



							var container_top_height		= document.getElementById(container_id).offsetTop;
							var container_bottom_height		= body_height - (container_top_height + container_height);


							leftData	= responsedata.skin_positions.L;
							topData		= responsedata.skin_positions.T;
							rightData	= responsedata.skin_positions.R;
							bottomData	= responsedata.skin_positions.B;












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
				{
		        	document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundImage="url('"+background_image+"')";

		        	if(topData == 1 && leftData == 0 && rightData == 0 && bottomData == 0)
		        	{
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundSize='100% '+container_top_height+'px';


		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.top		= '0px';
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.left	= '0px';
		        	}

		        	else if(topData == 0 && leftData == 0 && rightData == 0 && bottomData == 1)
		        	{
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundSize='100% '+container_bottom_height+'px';


		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.top		= (document.body.clientHeight - container_bottom_height)+'px';
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.left	= '0px';

		        	}
		        	else if(topData == 0 && leftData == 1 && rightData == 0 && bottomData == 0)
		        	{
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundSize=''+container_left_width+'px 100%';

		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.top		= '0px';
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.left	= '0px';
		        	}
		        	else if(topData == 0 && leftData == 0 && rightData == 1 && bottomData == 0)
		        	{
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundSize=''+container_right_width+'px 100%';

		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.top='0px';
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.left= (body_width - container_right_width)+'px';
		        	}
		        	else
		        	{
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.backgroundSize='100% 100%';


		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.top		= '0px';
		        		document.getElementById('xyz-SkinImageContainer-'+adunit).style.left	= '0px';
		        	}





		        	if(document.getElementById('xyz-SkinMarketContainer-'+adunit))
		        	{
		        		remove_element = document.getElementById('xyz-SkinMarketContainer-'+adunit);

		        		remove_element.parentNode.removeChild(remove_element);
		        	}





                    var SkinMarketContainerId ='xyz-SkinMarketContainer-'+adunit;
                        var SkinMarketContainer=document.createElement('div');
                        SkinMarketContainer.id=SkinMarketContainerId;
                        SkinMarketContainer.style.position= 'fixed';
                        SkinMarketContainer.style.top= '0px';
                        SkinMarketContainer.style.left= '0px';
                        SkinMarketContainer.style.width= '100%';
                        SkinMarketContainer.style.height= '';






		        	var XyzSkinClickUrl=responsedata.click_url;

		        	if(leftData == 1)
		        	{

		                        var SkinLeftContainerId ='xyz-SkinLeftContainer-'+adunit;
		                        var SkinLeftContainer=document.createElement('div');
		                        SkinLeftContainer.id=SkinLeftContainerId;
		                        SkinLeftContainer.style.height=container_left_height+'px';
		                        SkinLeftContainer.style.width=container_left_width+'px';
		                        SkinLeftContainer.style.zIndex=1000000001;
		                        SkinLeftContainer.style.position='absolute';
		                        SkinLeftContainer.style.top="0px";
		                        SkinLeftContainer.style.left='0px';
		                        SkinLeftContainer.style.cursor='auto';


		                        var SkinClick_L_Id ='xyz-SkinClickLID-'+adunit;
		                        var SkinClickL=document.createElement('a');
		                        SkinClickL.id=SkinClick_L_Id;
		                        SkinClickL.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickL.style.height=container_left_height+'px';
		                        SkinClickL.style.width=container_left_width+'px';
		                        SkinClickL.style.zIndex=1000000001;
		                        SkinClickL.style.display="block";
		                        SkinClickL.target="_blank";
		                        SkinLeftContainer.appendChild(SkinClickL);



		                        SkinMarketContainer.appendChild(SkinLeftContainer);




		        	}


		        	if(topData == 1)
		        	{
		                        var SkinTopContainerId ='xyz-SkinTopContainer-'+adunit;
		                        var SkinTopContainer=document.createElement('div');
		                        SkinTopContainer.id=SkinTopContainerId;
		                        SkinTopContainer.style.height=container_top_height+'px';
		                        SkinTopContainer.style.width=container_top_width+'px';
		                        SkinTopContainer.style.zIndex=1000000001;
		                        SkinTopContainer.style.position='absolute';
		                        SkinTopContainer.style.top="0px";
		                        SkinTopContainer.style.left='0px';
		                        SkinTopContainer.style.cursor='auto';


		        	 			var SkinClick_T_Id ='xyz-SkinClickTID-'+adunit;
		                        var SkinClickT=document.createElement('a');
		                        SkinClickT.id=SkinClick_T_Id;
		                        SkinClickT.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickT.style.height=container_top_height+'px';
		                        SkinClickT.style.width=container_top_width+'px';
		                        SkinClickT.style.zIndex=1000000001;
		                        SkinClickT.style.display="block";
		                        SkinClickT.target="_blank";
		                        SkinTopContainer.appendChild(SkinClickT);


		                        SkinMarketContainer.appendChild(SkinTopContainer);




		        	}





		        	if(rightData == 1)
		        	{
		                        var SkinRightContainerId ='xyz-SkinRightContainer-'+adunit;
		                        var SkinRightContainer=document.createElement('div');
		                        SkinRightContainer.id=SkinRightContainerId;
		                        SkinRightContainer.style.height=container_right_height+'px';
		                        SkinRightContainer.style.width=container_right_width+'px';
		                        SkinRightContainer.style.zIndex=1000000001;
		                        SkinRightContainer.style.position='absolute';
		                        SkinRightContainer.style.top="0px";
		                        SkinRightContainer.style.right='0px';
		                        SkinRightContainer.style.cursor='auto';



		                        var SkinClick_R_Id ='xyz-SkinClickRID-'+adunit;
		                        var SkinClickR=document.createElement('a');
		                        SkinClickR.id=SkinClick_R_Id;
		                        SkinClickR.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickR.style.height=container_right_height+'px';
		                        SkinClickR.style.width=container_right_width+'px';
		                        SkinClickR.style.zIndex=1000000001;
		                        SkinClickR.style.display="block";
		                        SkinClickR.target="_blank";
		                        SkinRightContainer.appendChild(SkinClickR);




		                        SkinMarketContainer.appendChild(SkinRightContainer);






		        	}


		        	if(bottomData == 1)
		        	{

		                        var SkinBottomContainerId ='xyz-SkinBottomContainer-'+adunit;
		                        var SkinBottomContainer=document.createElement('div');
		                        SkinBottomContainer.id=SkinBottomContainerId;
		                        SkinBottomContainer.style.height=container_bottom_height+'px';
		                        SkinBottomContainer.style.width=container_bottom_width+'px';
		                        SkinBottomContainer.style.zIndex=1000000001;
		                        SkinBottomContainer.style.position='absolute';





		                        SkinBottomContainer.style.top=(document.body.clientHeight - container_bottom_height)+'px';
		                        SkinBottomContainer.style.left='0px';
		                        SkinBottomContainer.style.cursor='auto';


		                        var SkinClick_B_Id ='xyz-SkinClickBID-'+adunit;
		                        var SkinClickB=document.createElement('a');
		                        SkinClickB.id=SkinClick_B_Id;
		                        SkinClickB.setAttribute('href',XyzSkinClickUrl);
		                        SkinClickB.style.height=container_bottom_height+'px';
		                        SkinClickB.style.width=container_bottom_width+'px';
		                        SkinClickB.style.zIndex=1000000001;
		                        SkinClickB.style.display="block";
		                        SkinClickB.target="_blank";
		                        SkinBottomContainer.appendChild(SkinClickB);


		                        SkinMarketContainer.appendChild(SkinBottomContainer);
		        	}

					document.body.appendChild(SkinMarketContainer);
		        }
  		}
	}



   if(typeof HideExpandable == 'undefined')
   {
		function HideExpandable(aduid)
		{
			document.getElementById('img-close-container-'+aduid).style.display='none';

			window['sizeintervalclose-'+aduid]= window.setInterval(function()
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

				if(hide_currentheight === 0)
				{
					spancontainer = document.getElementById('xyz-expandable-container-'+aduid);

					spancontainer.style.removeProperty("right");
					spancontainer.style.left='0px';

					window.clearInterval(window['sizeintervalclose-'+aduid]);

					var iFrameID   = document.getElementById('adm-id-'+aduid);

	    				iFrameID.contentWindow.postMessage('{"adcodeID":'+aduid+',"operation":"rotationEnable"}',"*");
				}

			}, 1);
		}
    }


	if(typeof ShowExpandable == 'undefined')
	{
		function ShowExpandable(expandableresponsedata,aduid)
		{
			spancontainer = document.getElementById('xyz-expandable-container-'+aduid);

			divcontainer  = document.getElementById('div-outer-'+aduid);


			offset = {x:0,y:0};
			scroll = {x:0,y:0};

			GetOffsetData(divcontainer,offset);
			GetScrollData(divcontainer.parentNode,scroll);

			posLeft = offset.x - scroll.x;
			posTop = offset.y - scroll.y;


			windowWidth  = window.innerWidth;
			windowHeight = window.innerHeight;

			sumWidth     = parseFloat(posLeft) + parseFloat(expandableresponsedata.width);

			if(sumWidth <= windowWidth || expandableresponsedata.width >= windowWidth)
			{
				spancontainer.style.removeProperty("right");
				spancontainer.style.left='0px';
			}
			else
			{
				spancontainer.style.removeProperty("left");
				spancontainer.style.right='0px';
			}


			spancontainer.style.top='0px';
			document.getElementById('img-close-container-'+aduid).style.bottom='0px';

			var heightflag=0;

			ratioheight=expandableresponsedata.height;

			if(ratioheight > 600)
			multiplay=4;
			else if(ratioheight > 400)
			multiplay=3;
			else
			multiplay=2;


	        addheight=multiplay;

			document.getElementById('xyz-expandable-container-'+aduid).style.width=expandableresponsedata.width+'px';


			window['sizeintervalexpshow-'+aduid]=window.setInterval(function(){


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


			if(heightflag ==1)
			{
				window.clearInterval(window['sizeintervalexpshow-'+aduid]);

				document.getElementById('img-close-container-'+aduid).style.display="block";

				currentheightplus=0;
			}

			}, 1);
		}
	}



	if(typeof LoadExpandable == 'undefined')
	{
	    function LoadExpandable(expandableresponsedata,aduid)
	    {
	    	if(document.getElementById("div-outer-"+aduid))
			{
		    	divouterelement=document.getElementById('div-outer-'+aduid);


		    	if(divouterelement.style.position != 'fixed')
		    	divouterelement.style.position="relative";


				spanelement = document.createElement("SPAN");
				spanelement.setAttribute('id','xyz-expandable-container-'+aduid);
				spanelement.setAttribute('style','position:absolute;overflow:hidden;width:0px;height:0px;z-index: 100000000000;');

				divouterelement.insertBefore(spanelement,divouterelement.childNodes[0]);



				spanelement_parent=document.getElementById('xyz-expandable-container-'+aduid);



		    	string_span="<a target='_blank' id='anchor-expandable-container-"+aduid+"' href='' >";
		    	string_span+="<img id='img-expandable-container-"+aduid+"' src='' />";
		    	string_span+="</a>";
		    	string_span+="<span class='img-close-box' id='img-close-container-"+aduid+"' style='position: absolute;right:0px;cursor:pointer;display:none;color: "+expandableresponsedata.color+";background-color: "+expandableresponsedata.background_color+";border:1px solid "+expandableresponsedata.background_color+";padding: 0px 7px 3px 7px;'>x</span>";

		        spanelement_parent.innerHTML=string_span;





		    	//if(document.getElementById('img-expandable-container-'+aduid).src == window.location.href)
		    	//{
					document.getElementById('img-expandable-container-'+aduid).style.width=expandableresponsedata.width+'px';
					document.getElementById('img-expandable-container-'+aduid).style.height=expandableresponsedata.height+'px';
					document.getElementById('img-expandable-container-'+aduid).src=base64_decode(expandableresponsedata.bannerpath);
		    	//}

				document.getElementById('img-expandable-container-'+aduid).style.display="block";

				document.getElementById('anchor-expandable-container-'+aduid).href=expandableresponsedata.clksurl;
			}
	    }
    }


	if(typeof FixStickyFrame == 'undefined')
	{
	    function FixStickyFrame(responsedata)
	    {
 			adcodeid			= responsedata.auid;
 			position			= responsedata.position;
 			credit_position		= responsedata.credit_position;
 			credit_alignment	= responsedata.credit_alignment;
		 	iframe_width		= responsedata.width;
			iframe_height		= responsedata.height;
			close_background	= responsedata.background_color;
			close_color			= responsedata.color;
			close_border		= responsedata.border;   		// 0 => Round  1 => Square
			close_position		= responsedata.close_position;
			close_image			= responsedata.close_image;
			inpage_push			= responsedata.inpage_push;


			window_width        = window.innerWidth;
			window_height       = window.innerHeight;

			window_width_half   = window_width/2;
			window_height_half  = window_height/2;



			left_position       = 0;
			top_position        = 0;
			iframe_height_minus = 0;
			iframe_width_minus  = 0;


			if(iframe_height > 25 && close_position ==0)
			iframe_height_minus=iframe_height-25;
			else
			iframe_height_minus=iframe_height;


			if(iframe_width > 25)
			iframe_width_minus=iframe_width-25;

			iframe_width_half=iframe_width/2;
			iframe_height_half=iframe_height/2;


			if(window_width_half > iframe_width_half)
			left_position=window_width_half-iframe_width_half;

			if(window_height_half > iframe_height_half)
			top_position=window_height_half-iframe_height_half;



			if(document.getElementById("div-outer-"+adcodeid))
			{
				document.getElementById('div-outer-'+adcodeid).style.position="fixed";
				document.getElementById('div-outer-'+adcodeid).style.zIndex="100001";
			}


			if(document.getElementById("minimize-adm-id-"+adcodeid))
			{
				document.getElementById('minimize-adm-id-'+adcodeid).style.display="block";
				document.getElementById('minimize-adm-id-'+adcodeid).style.backgroundColor=close_background;


				if(close_border ==0)
				document.getElementById('minimize-adm-id-'+adcodeid).style.borderRadius="15px";

				document.getElementById('minimize-adm-id-'+adcodeid).style.color=close_color;


				if(close_image == "")
				{
					document.getElementById('minimize-adm-id-'+adcodeid).style.border="1px solid "+close_background;
					document.getElementById('minimize-adm-id-'+adcodeid).style.boxSizing="border-box";

					document.getElementById('minimize-adm-id-'+adcodeid).innerHTML="<span>x</span>";
				}
				else
				document.getElementById('minimize-adm-id-'+adcodeid).style.backgroundImage="url('"+close_image+"')";
			}


			if(document.getElementById("div-outer-"+adcodeid))
			{
				if(position == 'TL')
				{
					document.getElementById('div-outer-'+adcodeid).style.top  = "0px";
					document.getElementById('div-outer-'+adcodeid).style.left = "0px";
				}
				else if(position == 'TC')
				{
					document.getElementById('div-outer-'+adcodeid).style.top="0px";
					document.getElementById('div-outer-'+adcodeid).style.left=left_position+"px";
				}
				else if(position == 'TR')
				{
					if(inpage_push == 0)
					{
					document.getElementById('div-outer-'+adcodeid).style.top   = "0px";
					document.getElementById('div-outer-'+adcodeid).style.right = "0px";
					}
					else if(inpage_push == 1)
					{
						document.getElementById('div-outer-'+adcodeid).style.top   = "-"+iframe_height+"px";
						document.getElementById('div-outer-'+adcodeid).style.right = "15px";
						var currentTop = "-"+iframe_height; 
						var inpagePushInterval = setInterval(function() {
							if(currentTop < 15) 
							{
								currentTop = parseInt(currentTop) + 1;
								document.getElementById('div-outer-'+adcodeid).style.top = currentTop + "px";
							} else {
								clearInterval(inpagePushInterval); 
							}
						}, 10); 						
					}
				}
				else if(position == 'ML')
				{
					document.getElementById('div-outer-'+adcodeid).style.top=top_position+"px";
					document.getElementById('div-outer-'+adcodeid).style.left="0px";
				}
				else if(position == 'MC')
				{
					document.getElementById('div-outer-'+adcodeid).style.top=top_position+"px";
					document.getElementById('div-outer-'+adcodeid).style.left=left_position+"px";
				}
				else if(position == 'MR')
				{
					document.getElementById('div-outer-'+adcodeid).style.top=top_position+"px";
					document.getElementById('div-outer-'+adcodeid).style.right="0px";
				}
				else if(position == 'BL')
				{
					document.getElementById('div-outer-'+adcodeid).style.bottom  = "0px";
					document.getElementById('div-outer-'+adcodeid).style.left    = "0px";
				}
				else if(position == 'BC')
				{
					document.getElementById('div-outer-'+adcodeid).style.bottom="0px";
					document.getElementById('div-outer-'+adcodeid).style.left=left_position+"px";
				}
				else if(position == 'BR')
				{
					document.getElementById('div-outer-'+adcodeid).style.bottom  = "0px";
					document.getElementById('div-outer-'+adcodeid).style.right   = "0px";
				}
			}

			if(document.getElementById("minimize-adm-id-"+adcodeid))
			{
				if(iframe_width > window_width || iframe_height > window_height)
				{
					if(position == 'TL' || position == 'TC' || position == 'ML' || position == 'MC' || position == 'BL' || position == 'BC')
					{
						document.getElementById('minimize-adm-id-'+adcodeid).style.top  = "0px";
						document.getElementById('minimize-adm-id-'+adcodeid).style.left = "0px";
					}
					else
					{
						document.getElementById('minimize-adm-id-'+adcodeid).style.top   = "0px";
						document.getElementById('minimize-adm-id-'+adcodeid).style.right = "0px";
					}
				}
				else
				{
					if(position == 'TL' || position == 'TC' || position == 'ML' || position == 'MC')
					{
						if(close_position == 0)
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.bottom = "0px";

							if(credit_position ==0 && credit_alignment ==1)  // Bottom Right
							document.getElementById('minimize-adm-id-'+adcodeid).style.left="0px";
							else
							document.getElementById('minimize-adm-id-'+adcodeid).style.right="0px";
						}
						else
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.bottom = "-25px";
							document.getElementById('minimize-adm-id-'+adcodeid).style.right = "0px";
						}
					}
					else if(position == 'TR' || position == 'MR')
					{
						if(close_position == 0)
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.bottom = "0px";

							if(credit_position ==0 && credit_alignment ==0)  // Bottom Left
							document.getElementById('minimize-adm-id-'+adcodeid).style.right="0px";
							else
							document.getElementById('minimize-adm-id-'+adcodeid).style.left="0px";
						}
						else
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.bottom = "-25px";
							document.getElementById('minimize-adm-id-'+adcodeid).style.left = "0px";
						}
					}
					else if(position == 'BL' || position == 'BC')
					{
						if(close_position ==0)
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.top="0px";

							if(credit_position ==1 && credit_alignment ==1)  // Top Right
							document.getElementById('minimize-adm-id-'+adcodeid).style.left="0px";
							else
							document.getElementById('minimize-adm-id-'+adcodeid).style.right="0px";
						}
						else
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.top="-25px";
							document.getElementById('minimize-adm-id-'+adcodeid).style.right="0px;";

						}
					}
					else if(position == 'BR')
					{
						if(close_position == 0)
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.top="0px";


							if(credit_position == 1 && credit_position == 0)  // Top Left
							document.getElementById('minimize-adm-id-'+adcodeid).style.right="0px";
							else
							document.getElementById('minimize-adm-id-'+adcodeid).style.left="0px";
						}
						else
						{
							document.getElementById('minimize-adm-id-'+adcodeid).style.top="-25px";
							document.getElementById('minimize-adm-id-'+adcodeid).style.left="0px";
						}
					}


			}

}





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

	this.post_sticky_handler = function(aduid,urldata,e)
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
				var responsedataSticky=JSON.parse(response);

				if(responsedataSticky !="")
				{
	                if(responsedataSticky.operation =='stickyads' && responsedataSticky.auid == aduid)
					{
						FixStickyFrame(responsedataSticky);

						window.addEventListener("resize", function()
		                {
		                    FixStickyFrame(responsedataSticky);
		                });
					}
				}
			} catch (e) {}
		}
     }


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







	this.post_trackdata_handler = function(aduid,urldata,e)
	{
  		var response 	= e.data;

  		try
  		{
    			responsedata=JSON.parse(response);

    			if(responsedata !="")
    			{
              if(responsedata.operation =='trackdata' && responsedata.auid == aduid)
      				{
  					        cookie_content_value = "";

                  	cookie_content_data  = Get_Cookie(responsedata.dataname);

                    	//For manage cookie value when rotating ads automatically
                  	if(cookie_content_data != "" && cookie_content_data != null)
                  	{
                    		cookie_content_data_array = cookie_content_data.split('_');

                    		current_array_length      = cookie_content_data_array.length;

    						        current_array	            = new Array();

                    		if(current_array_length > 0)
                    		{
                      			for(i = 0;i < current_array_length;i++)
                      			{
                      				   cookie_content_replace = cookie_content_data_array[i].split("-");

                                 if(parseInt(cookie_content_replace[2]) > parseInt(responsedata.currentTime))
      								           current_array[cookie_content_replace[0]] = parseInt(cookie_content_replace[1])+"-"+parseInt(cookie_content_replace[2]);
                      			}
                    		}

                    		if(current_array.length > 0)
                    		{
    	                		  cookie_content_new = responsedata.content;

    	                		  cookie_content_new_array  = cookie_content_new.split('_');

    							          cookie_content_new_length = cookie_content_new_array.length;

              							if(cookie_content_new_length > 0)
              							{
                								for(ii = 0;ii < cookie_content_new_length;ii++)
                		            {
                  									cookie_content_new_array_split = cookie_content_new_array[ii].split("-");

                  									if(cookie_content_new_array_split.length > 0)
                										current_array[cookie_content_new_array_split[0]] = parseInt(cookie_content_new_array_split[1])+"-"+parseInt(cookie_content_new_array_split[2]);
                								}

              								  new_current_array_length = current_array.length;

                								if(new_current_array_length > 0)
                								{
                  									current_array.forEach(function(array_content, array_index)
                  									{
                    										if(cookie_content_value != "")
                    										cookie_content_value = cookie_content_value+"_";

                    										cookie_content_value = cookie_content_value+array_index+"-"+array_content;
                  									});
                								}
              							}
    							          else
    		                	  cookie_content_value = cookie_content_data;
    			             }
    		               else
    		               cookie_content_value = responsedata.content;
    		          }
    		          else
    		          cookie_content_value = responsedata.content;

    			  		  Set_Track_Cookie(responsedata.dataname,cookie_content_value,responsedata.expiry,'/');
  				    }
  			   }
		    } catch (e) {}
    }




		this.post_skin_handler = function(aduid,urldata,e)
		{
			urldata_content = urldata;

			var response 	= e.data;
				//response	= response.replace(/'/g,'"');
				//Verify its required or not


			urldata=urldata.replace("https://","");
			urldata=urldata.replace("http://","");
			urldata=urldata.replace("www.","");

			urlorigin=e.origin;

			urlorigin=urlorigin.replace("https://","");
			urlorigin=urlorigin.replace("http://","");
			urlorigin=urlorigin.replace("www.","");


			var urlindex	= urldata.indexOf(urlorigin);


			if(urlindex == 0)
			{
				try
	            {
					responsedata = JSON.parse(response);

	                if(responsedata !="")
	                {
	                    if(responsedata.operation =='skinAdRender' && responsedata.auid == aduid)
	                    {
	                    	skinresponsedata = responsedata;

							var body_width  = document.documentElement.clientWidth || document.body.clientWidth || document.body.offsetWidth;
							var body_height = document.documentElement.clientHeight || document.body.clientHeight || document.body.offsetHeight;


	                        var container_id    	= responsedata.container_id;
                            var container_width 	= document.getElementById(container_id).offsetWidth;
                        	var container_height	= document.getElementById(container_id).offsetHeight;

							var dataoffset = document.getElementById(container_id).getBoundingClientRect();

							var container_left_width		= dataoffset.left;
							var container_right_width		= body_width - dataoffset.right;

							var container_left_height		= body_height;
							var container_right_height		= body_height;


							var container_top_width			= body_width;
							var container_bottom_width		= body_width;


							var container_top_height		= document.getElementById(container_id).offsetTop;
							var container_bottom_height		= body_height - (container_top_height + container_height);


							leftData	= responsedata.skin_positions.L;
							topData		= responsedata.skin_positions.T;
							rightData	= responsedata.skin_positions.R;
							bottomData	= responsedata.skin_positions.B;

							if(container_bottom_height < 0)
							container_bottom_height = 0;

               				if(parseInt(body_width) > parseInt(container_width))
	                    	{
	                             var iFrameID   = document.getElementById('adm-id-'+aduid);
	                             var skinObject = {"adcodeID":aduid, "operation":'skinAdRendered', "adid" : responsedata.adid};
	                             var skinJSON   = JSON.stringify(skinObject);

	                             iFrameID.contentWindow.postMessage(skinJSON,urldata_content);

                                 var background_images	= responsedata.skin_banners;
                                 var skin_side_heights	= responsedata.skin_height;



		                        var SkinImageContainerId ='xyz-SkinImageContainer-'+aduid;
		                        var SkinImageContainer=document.createElement('div');
		                        SkinImageContainer.id=SkinImageContainerId;
		                        SkinImageContainer.style.backgroundRepeat= 'no-repeat';
		                        SkinImageContainer.style.position= 'fixed';

		                        SkinImageContainer.style.width= '100%';
		                        SkinImageContainer.style.height= '100%';

		                        SkinImageContainer.style.zIndex='-1';
		                        document.body.appendChild(SkinImageContainer);


		                        SkinReplace(background_images,skin_side_heights,aduid,responsedata);

		                        window.addEventListener("resize", function()
		                        {
		                        	SkinReplace(background_images,skin_side_heights,aduid,skinresponsedata);
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
				//response	= response.replace(/'/g,'"');
				//Verify its required or not


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
						if(responsedata.operation =='expandable_open')
						{
							LoadExpandable(responsedata,aduid);

						 	window['expandableinterval1-'+responsedata.auid]=window.setInterval(function()
							{
								ShowExpandable(responsedata,aduid);

								if(document.getElementById("img-close-container-"+aduid))
								document.getElementById("img-close-container-"+aduid).onclick = function(){ HideExpandable(aduid); };

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
						Set_Cookie_Data('interstitial_show_'+responsedata.auid,1,exptime,"/") ;
					}
					else if(responsedata.operation =='open' && responsedata.auid == aduid)
					{
						var interstitialBox           = document.getElementById('xyz-interstitial-container-'+responsedata.auid);
						var interstitialBackgroundBox = document.getElementById('xyz-interstitial-background-'+responsedata.auid);

					    	interstitialBox.style.width   = (parseFloat(responsedata.width) + 25) + 'px';
					    	interstitialBox.style.height  = (parseFloat(responsedata.height) + 25) + 'px';
              			
						interstitialBox.style.display  = "grid";
						interstitialBox.style.position = "fixed";
						interstitialBox.style.placeItems = "center";
						
					

						interstitialBackgroundBox.style.display = "block";
  						
						interstitialBox.style.left = '50%';
              					interstitialBox.style.top  = '50%';
						interstitialBox.style.transform  = 'translate(-50%, -50%)';


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

			if(urlindex == 0)
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

							iFrameID.style.width   = responsedata.width+'px' ;
							if(document.getElementById("adm-container-"+aduid))
							{
								if(responsedata.nativeResponsive ==0)
								{
									document.getElementById("adm-container-"+aduid).style.width  = responsedata.width+'px';
							        	document.getElementById("adm-container-"+aduid).style.height = responsedata.height+'px';
							    	}
							    	else
							    	document.getElementById("adm-container-"+aduid).style.width  = '100%';
						    	}



							if(document.getElementById("data_"+aduid))
							{
								if(responsedata.nativeResponsive ==0)
								{
									document.getElementById("data_"+aduid).style.width  = responsedata.width+'px';
							        	document.getElementById("data_"+aduid).style.height = responsedata.height+'px';
							    	}
							    	else
							    	document.getElementById("data_"+aduid).style.width  = '100%';
						    	}

                  //For resize when initial load, if required
                  if(responsedata.nativeResponsive == 1)
                  {


                  	    var iFrameID     = document.getElementById('adm-id-'+aduid);

                  			if(document.getElementById("adm-container-"+aduid))
                  			var currentWidth = document.getElementById("adm-container-"+aduid).offsetWidth;
                  			else if(document.getElementById("data_"+aduid))
                  			var currentWidth = document.getElementById("data_"+aduid).offsetWidth;

                  		  iFrameID.contentWindow.postMessage('{"adcodeID":'+aduid+',"currentWidth":'+currentWidth+',"operation":"nativeResize"}',"*");

                  }






						}
					}
				}
				} catch (e) {}
			}
        }


	   this.native_resize_handler = function resize_adunit(aduid)
	   {
			var iFrameID     = document.getElementById('adm-id-'+aduid);

			if(document.getElementById("adm-container-"+aduid))
			var currentWidth = document.getElementById("adm-container-"+aduid).offsetWidth;
			else if(document.getElementById("data_"+aduid))
			var currentWidth = document.getElementById("data_"+aduid).offsetWidth;

	    			 iFrameID.contentWindow.postMessage('{"adcodeID":'+aduid+',"currentWidth":'+currentWidth+',"operation":"nativeResize"}',"*");
	   }


		this.post_iframe_handler = function(aduid,urldata,e)
		{
			var response 	= e.data;

			urldata			= urldata.replace("https://","");
			urldata			= urldata.replace("http://","");
			urldata			= urldata.replace("www.","");

			urlorigin		= e.origin;

			urlorigin		= urlorigin.replace("https://","");
			urlorigin		= urlorigin.replace("http://","");
			urlorigin		= urlorigin.replace("www.","");

			var urlindex	= urldata.indexOf(urlorigin);



			if(urlindex ==0)
			{
				try
				{
					responsedata=JSON.parse(response);
					if(responsedata !="")
					{
		               	if(responsedata.operation =='iframe_resize' && responsedata.auid == aduid)
						{
							if(document.getElementById("adm-container-"+aduid))
							{
								document.getElementById("adm-container-"+aduid).style.width  = responsedata.width+'px';
					            document.getElementById("adm-container-"+aduid).style.height = responsedata.height+'px';


								if(document.getElementById("adm-id-"+aduid))
								{
									document.getElementById("adm-id-"+aduid).style.width  = responsedata.width+'px';
						          	document.getElementById("adm-id-"+aduid).style.height = responsedata.height+'px';
								}


								if(document.getElementById("div-outer-"+aduid))
								{
									document.getElementById("div-outer-"+aduid).style.width  = responsedata.width+'px';
						          	document.getElementById("div-outer-"+aduid).style.height = responsedata.height+'px';
								}
							}
						}
					}
				} catch (e) {}
			}
	     }




    var async=0;

    if(document.currentScript.async)
    var async=1;


	if(screen.width < 576)
	var device_type='xsmall_dev_adblock';
	else if(screen.width >= 576 && screen.width < 768)
	var device_type ='small_dev_adblock';
	else if(screen.width >= 768 && screen.width < 992)
	var device_type ='medium_dev_adblock';
	else if(screen.width >= 992)
	var device_type='large_dev_adblock';




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
	var adcodeDisplay=ItemDataScript_parameter_seperate[5];
	else
	var adcodeDisplay=0;


    if(ItemDataScript_parameter_seperate.length >6 && ItemDataScript_parameter_seperate[6] > 0)
    {
    	responsive  = 1;
    	block_id	= ItemDataScript_parameter_seperate[6];
    }
    else
    {
    	responsive  = 0;
    	block_id	= 0;
    }

	var native       = 0;
	var interstitial = 0;
	var skin         = 0;
	var inpagepush   = 0;
	var pop          = 0;
	var video        = 0;

	if(adcodeDisplay == 1)
	native = 1;
	else if(adcodeDisplay == 5 || displaytype == 5)
	interstitial = 1;
	else if(adcodeDisplay == 9 || displaytype == 9)
	{
		pop         = 1;
		displaytype = 1;
	}
	else if(adcodeDisplay == 13 || displaytype == 13)
	{
		video       = 1;
		displaytype = 1;
	}
	else if(adcodeDisplay == 14 || displaytype == 14)
	skin = 1;
	else if(adcodeDisplay == 19)
	inpagepush = 1;


    if(skin == 1)
    {
         height = 0;
         width  = 0;
    }

    adSectionWidth = 0;
    if(document.getElementById("adm-container-"+aduid))
    adSectionWidth = document.getElementById("adm-container-"+aduid).offsetWidth;
    else if(document.getElementById("data_"+aduid))
    adSectionWidth = document.getElementById("data_"+aduid).offsetWidth;



	if(native == 1)
	width = adSectionWidth;


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
			$getip = str_replace(" ","",$getip); //Not mandatory.If space exists in IP, it should be remove

			$getip_array=explode(',',$getip);

			return trim($getip_array[count($getip_array) -1]);
		}
		else
		return trim($getip);
	}

	function check_lan_ip($ip)
	{
		if (!isset($ip) || $ip =='' || $ip ==0 || ($ip >= '127.0.0.0' && $ip <= '127.255.255.255') || ($ip >= '10.0.0.0' && $ip <= '10.255.255.255') || ($ip >= '172.16.0.0' && $ip <= '172.31.255.255') || ($ip >= '192.168.0.0' && $ip <= '192.168.255.255') || ($ip >= '169.254.0.0' && $ip <= '169.254.255.255'))
		return false;

		return $ip;
	}

	$time=time();
?>

			cpc_impression  = "";
			cpm_impression  = "";
			cpa_impression  = "";
			cpd_impression  = "";
			cpv_impression  = "";
			html_impression = "";
      		pop_impression  = "";


			if(displaytype == 0 || displaytype == 4)
			cpc_impression = Get_Cookie('_data_cpc');

			if((displaytype == 1 || displaytype == 4) && pop == 0 && video == 0)
			{
				cpm_impression  = Get_Cookie('_data_cpm');
				html_impression = Get_Cookie('_data_html');
			}

			if(displaytype == 6 || displaytype == 4)
			cpa_impression = Get_Cookie('_data_cpa');

			if(displaytype == 3)
			cpd_impression = Get_Cookie('_data_cpd');

      		if(displaytype == 1 && pop == 1)
			pop_impression = Get_Cookie('_data_pop');

			if(displaytype == 1 && video == 1)
			cpv_impression = Get_Cookie('_data_cpv');


			var url=ItemDataScript_dir;
			url=url+"/&aduid="+aduid;
			url=url+"&pid="+pid;
			url=url+"&width="+width;
			url=url+"&height="+height;
			url=url+"&displaytype="+displaytype;
			
			if(native == 1)
            url=url+"&native="+native;
			
			if(interstitial == 1)
			url=url+"&interstitial="+interstitial;
			
			if(skin == 1)
			url=url+"&skin="+skin;
			
			if(inpagepush == 1)
			url=url+"&inpagepush="+inpagepush;

			if(pop == 1)
			url=url+"&pop="+pop;
			if(video == 1)
			url=url+"&video="+video;
			
            url=url+"&device_type="+device_type;
            url=url+"&block_id="+block_id;
            url=url+"&responsive="+responsive;
            url=url+"&adcode_count="+adcode_count;
            url=url+"&adSectionWidth="+adSectionWidth;

            url=url+"&page_data=<?php echo md5(get_user_ip()."ADM-DATA-ADM".$_SERVER['HTTP_USER_AGENT'].($time+40)); ?>";
            url=url+"&time=<?php echo $time; ?>";

            url=url+"&deliver="+utf8_encode(window.location.hostname.replace('www.',''));
			url=url+"&search_keywords="+utf8_encode(search_keywords);
			url=url+"&page_referrer="+base64_encode(page_referrer);
			url=url+"&page_title="+utf8_encode(page_title);
			url=url+"&meta_description="+utf8_encode(meta_description);


			if(cpc_impression != "" && cpc_impression != null)
			url=url+"&cpc_impression="+base64_encode(cpc_impression);

			if(cpm_impression != "" && cpm_impression != null)
			url=url+"&cpm_impression="+base64_encode(cpm_impression);

			if(cpa_impression != "" && cpa_impression != null)
			url=url+"&cpa_impression="+base64_encode(cpa_impression);

			if(cpd_impression != "" && cpd_impression != null)
			url=url+"&cpd_impression="+base64_encode(cpd_impression);

			if(cpv_impression != "" && cpv_impression != null)
			url=url+"&cpv_impression="+base64_encode(cpv_impression);

			if(html_impression != "" && html_impression != null)
			url=url+"&html_impression="+base64_encode(html_impression);

      			if(pop_impression != "" && pop_impression != null)
			url=url+"&pop_impression="+base64_encode(pop_impression);

			iframe_src="";

			var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
			var eventer = window[eventMethod];

			var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
			var resizeEvent  = eventMethod == "attachEvent" ? "onresize" : "resize";


			if(pop == 1)
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
				if(native == 1)
				iframe_src="<iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' scrolling='no' style='width:"+width+"px;'></iframe>";
				else
				{
					if(interstitial == 1)
					iframe_src+="<div id='div-outer-"+aduid+"' style='height:"+height+"px;width:"+width+"px;position: relative;'><iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:"+height+"px;width:"+width+"px;' scrolling='no' ></iframe></div>";
				    else
				    {
				    	if(skin == 1)
				        iframe_src+="<iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:0px;width:0px;position:relative;' scrolling='no'></iframe>";
				    	else
				    	{

				    	//display: inline-table; removed from below div-outer

							iframe_src+="<div id='div-outer-"+aduid+"' style='height:"+height+"px;width:"+width+"px;position: relative;margin:0px auto;'><iframe id='adm-id-"+aduid+"' name='adm-id-"+aduid+"' frameborder='0' src='"+url+"' allowtransparency='true' style='height:"+height+"px;width:"+width+"px;position: relative;top: 0px;bottom: 0px;right: 0px;left: 0px;' scrolling='no' ></iframe>";
							iframe_src+="<div id='minimize-adm-id-"+aduid+"' style='position:absolute;z-index:1002;width: 25px;height: 25px;text-align: center;cursor: pointer;display:none;' onclick=\"document.getElementById('adm-container-"+aduid+"').remove();\"></div>";
							iframe_src+="</div>";
						}
					}
				}
			}


		if(interstitial == 1)
		{
			var interstitial_view=Get_Cookie('interstitial_show_'+aduid);

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

						//New code
						interstitialContainer.style.boxSizing="border-box";
						interstitialContainer.style.borderRadius="10px";
						interstitialContainer.style.backgroundColor="#FFFFFF";
						interstitialContainer.style.boxShadow="0 0 15px rgba(0,0,0,.3)";						
						interstitialContainer.style.transition="375ms";
						interstitialContainer.style.animationName="orquidea-slideup";
						interstitialContainer.style.animationDuration="1s";
						interstitialContainer.style.animationFillMode="both";
						interstitialContainer.style.animationDelay="2s";
						//New code

						document.body.appendChild(interstitialContainer);


						document.getElementById(interstitial_container_id).innerHTML=iframe_src;
						
						var interstitialBackGround=document.createElement('div');
						interstitialBackGround.id=interstitial_background_id;
						interstitialBackGround.style.height='100%';
						interstitialBackGround.style.width='100%';
						interstitialBackGround.style.zIndex=1000000000;
						interstitialBackGround.style.position="fixed";
						interstitialBackGround.style.top="0px";
						interstitialBackGround.style.left="0px";
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
			if(async==1 && pop == 1)
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
            else if(async==1 && pop == 0)
            {
            	if(document.getElementById("adm-container-"+aduid))
            	{
            		//////////////////// For ad display center ////////////////////
            		document.getElementById("adm-container-"+aduid).style.width  = width+"px";
            		document.getElementById("adm-container-"+aduid).style.height = height+"px";
            		document.getElementById("adm-container-"+aduid).style.margin = "0px auto";
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


			if(pop == 0)
			{
				eventer(messageEvent, this.post_expandable_handler.bind(null,this.aduid,url),false);  	// For set click url & expandable banner

			   	eventer(messageEvent, this.post_sticky_handler.bind(null,this.aduid,url),false);
			}


			if(native ==1)
			{
				eventer(messageEvent, this.post_native_handler.bind(null,this.aduid,url),false);

				eventer(resizeEvent, this.native_resize_handler.bind(null,this.aduid) , false);
			}

 			if(skin == 1)
		 	eventer(messageEvent, this.post_skin_handler.bind(null,this.aduid,url),false);
		}

		if(pop == 0)
		{
			eventer(messageEvent, this.post_noads_handler.bind(null,this.aduid,url),false);

        	if(responsive == 1)
        	eventer(messageEvent, this.post_iframe_handler.bind(null,this.aduid,url),false);
		}


		eventer(messageEvent, this.post_trackdata_handler.bind(null,this.aduid,url),false);

	return;

})();
