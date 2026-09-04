function sortSelect(selElem) 
        {
                var tmpAry = new Array();
                for (var i=0;i<selElem.options.length;i++) {
                        tmpAry[i] = new Array();
                        tmpAry[i][0] = selElem.options[i].text;
                        tmpAry[i][1] = selElem.options[i].value;
                }
                tmpAry.sort();
                while (selElem.options.length > 0) {
                    selElem.options[0] = null;
                }
                for (var i=0;i<tmpAry.length;i++) {
                        var op = new Option(tmpAry[i][0], tmpAry[i][1]);
                        selElem.options[i] = op;
                }
                return selElem;
        }

function CreateResponsiveTable(tableid)
{
	if($(window).width() <768)
	{
    if($('#'+tableid+'-mobile').length ==0)
    {
        var columncount=$('#'+tableid+' tr:first td').length;
        var rowcount=$('#'+tableid+' > tbody > tr').length;
        
    
        $(document.createElement('table')).addClass(tableid+'-mobile').insertAfter('#'+tableid);
    
        $('.'+tableid+'-mobile').attr('id',tableid+'-mobile');
    
        string='';
    
        for(i=1;i<rowcount;i++)
        {
            string1='';
			flag=0;
			
			
			tabletrid=$('#'+tableid+' tr').eq(i).attr('id');
			
						
			if(typeof tabletrid === 'undefined') 
			stringdata='';
			else
			stringdata=' id="table-'+tabletrid+'" ';
			
            string+='<tr><td><table '+stringdata+'>';
            
            
            for(ii=0;ii<columncount;ii++)
            {
                headtitle=$('#'+tableid+' tr').eq(0).children("td").eq(ii).html();
                
            
				colunmcount1=$('#'+tableid+' tr').eq(i).children("td").length;
			
				if(colunmcount1 >1)
				{
					
					
					tdclass=$('#'+tableid+' tr').eq(i).children("td").eq(ii).attr('class');
					
					
					if(typeof tdclass === 'undefined') 
					stringdata1='';
					else
					stringdata1=' class="'+tdclass+'" ';
					
					
										
					contentdata=$('#'+tableid+' tr').eq(i).children("td").eq(ii).html();
					
                	string1+='<tr '+stringdata1+'><td class="table-head-responsive">'+headtitle+'</td><td>'+contentdata+'</td></tr>';
				
				}
				else
				{
					tdclass=$('#'+tableid+' tr').eq(i).children("td").eq(0).attr('class');
					
					
					if(typeof tdclass === 'undefined') 
					stringdata1='';
					else
					stringdata1=' class="'+tdclass+'" ';					
					
					contentdata=$('#'+tableid+' tr').eq(i).children("td").eq(0).html();
					
					string1+='<tr '+stringdata1+'><td class="table-head-responsive">'+headtitle+'</td>';
					
					if(flag ==0)
					{
						flag=1;
						string1+='<td rowspan="'+columncount+'">'+contentdata+'</td>';
						
					}
					
					string1+='</tr>';
				}
            }
            string+=string1+'</table></td></tr>';
        }
    
        $('#'+tableid+'-mobile').html(string);
    
    
        $('#'+tableid).hide();
    }
    else
	{
        $('#'+tableid+'-mobile').show();
		 $('#'+tableid).hide();
	}
	}
	
	else
	{
	if ($('#'+tableid+'-mobile').length >0)
	 $('#'+tableid+'-mobile').hide();	
	 
	$('#'+tableid).show();
	}
}




function PopWindowUnder(url,width,height) 
{
    var main_window  = (top != self && typeof(top.document.location.toString()) === 'string') ? top : self;



    var useragent = function() {
        var agentname = navigator.userAgent.toLowerCase();
        var agentbrowser = {
            webkit: /webkit/.test(agentname),
            mozilla: (/mozilla/.test(agentname)) && (!/(compatible|webkit)/.test(agentname)),
            chrome: /chrome/.test(agentname),
            msie: (/msie/.test(agentname)) && (!/opera/.test(agentname)),
            firefox: /firefox/.test(agentname),
            safari: (/safari/.test(agentname) && !(/chrome/.test(agentname))),
            opera: /opera/.test(agentname)
        };
        agentbrowser.version = (agentbrowser.safari) ? (agentname.match(/.+(?:ri)[\/: ]([\d.]+)/) || [])[1] : (agentname.match(/.+(?:ox|me|ra|ie)[\/: ]([\d.]+)/) || [])[1];
        return agentbrowser;
    }();



    function PopWindow(url,width,height) 
    {
        var window_data = 'toolbar=no,scrollbars=yes,location=yes,statusbar=yes,menubar=no,resizable=1,width='+width+',height='+height;

            pop_object = main_window.window.open(url,'',window_data);
            if(pop_object) 
            {
            	try {
                    pop_object.blur();
                    pop_object.opener.window.focus();
                    window.self.window.focus();
                    window.focus();

                    
                    //if (useragent.firefox) closewindow();
                    if (useragent.webkit) closetab();
                    else	
                    {
                        setTimeout(function() {
                            pop_object.blur();
                            pop_object.opener.window.focus();
                            window.self.window.focus();
                            window.focus();
                        }, 1000);
                    }

                    
                } catch (e) {}
            }
    }


    



    function closewindow() {
        var newwindow = window.open('about:blank');
        newwindow.focus();
        newwindow.close();
    }

    function closetab() 
    {
        var temp = '';
        var newtab = document.createElement("a");
        newtab.href   = "data:text/html,<scr"+temp+"ipt>window.close();</scr"+temp+"ipt>";
        document.getElementsByTagName("body")[0].appendChild(newtab);

        var clickevent = document.createEvent("MouseEvents");
        clickevent.initMouseEvent("click", false, true, window, 0, 0, 0, 0, 0, true, false, false, true, 0, null);
        newtab.dispatchEvent(clickevent);

        newtab.parentNode.removeChild(newtab);

        
    	window.open(newtab.href).close();
    }

   
    PopWindow(url,width,height);
    
}

function set_jnotice(type,msg)
{
	$("#system_notice_area").css({"zIndex":"12001"});
	$("#system_notice_area").animate({
		opacity : 'show',
		height : 'show'
		}, 400);
	
	icondata = "";
	
	if(type==0)
	{
		$(".system_notice_area").removeClass("system_notice_area_style1").addClass("system_notice_area_style0");
	
		icondata = ' fa-exclamation-circle ';
		
		icondata = '<img src="images/failure_red.png" />';
	}
	else
	{
		$(".system_notice_area").removeClass("system_notice_area_style0").addClass("system_notice_area_style1");
	
		icondata = '<img src="images/tick_green.png" />';
	}
	
	
	$("#system_notice_area_tr").html('<td class="notice-icon-box">'+icondata+'</td><td class="notice-content-box">'+msg+'</td><td class="notice-close-box"><span id="system_notice_area_dismiss">X</span></td>');
	
	
    if($('#system_notice_area').length > 0)
    {
	    body_width   = $('body').outerWidth();
	    notice_width = $('#system_notice_area').outerWidth();

	    width_minus  = body_width - notice_width;

	    if(width_minus > 0)
	    {
		    width_divide = width_minus / 2;

		    $('#system_notice_area').css("left",width_divide+'px');
	    }	
	    else
	    $('#system_notice_area').css("left",'0px');
    }	
	
	
	
	setTimeout(function(){$("#system_notice_area").hide(200);},4000)

	jQuery('#system_notice_area_dismiss').click(function() {
		jQuery('#system_notice_area').animate({
			opacity : 'hide',
			height : 'hide'
		}, 500);

	});
}





