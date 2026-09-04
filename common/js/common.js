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

function set_jnotice(type,msg,imagePath = "")
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

		icondata = '<img src="'+imagePath+'images/failure_red.png" />';
	}
	else
	{
		$(".system_notice_area").removeClass("system_notice_area_style0").addClass("system_notice_area_style1");

		icondata = '<img src="'+imagePath+'images/tick_green.png" />';
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



function get_suggestion_result(table_name, field_name,input_value,url,type)
{

		$.ajax({
		type: "POST",
		url: url,
		data:'tablename='+table_name+'&filedname='+field_name+'&inputvalue='+input_value+'&type='+type,
		beforeSend: function(){
			$("#"+field_name).css("background","#FFF url('images/load.gif') no-repeat 150px");
		},
		success: function(data)
		{
			$("#user-datalist").html(data);
			$("#"+field_name).css("background","#FFF");
		}
		});


}

function change_theme(theme,basePath)
{
	document.cookie = "active_theme="+theme+"; path="+basePath;
	location.reload();
}

function LoadLocaleFile(language,languageurl,logedin)
{

	if(language != "")
	{
		$.ajax(
		{
			type: "GET",
			url: languageurl+language,
			success: function(msg)
			{
				if(logedin == 1)
				{
					if($('#top-ad-iframe').length >0)
					$('#top-ad-iframe').attr('src',$('#top-ad-iframe').attr('src'));

					if($('#top-ad-iframe1').length >0)
					$('#top-ad-iframe1').attr('src',$('#top-ad-iframe1').attr('src'));

					if($('#top-adcode-iframe').length >0)
					$('#top-adcode-iframe').attr('src',$('#top-adcode-iframe').attr('src'));

					if($('#ad-keyword-iframe').length >0)
					$('#ad-keyword-iframe').attr('src',$('#ad-keyword-iframe').attr('src'));

					if($('#ad-location-iframe').length >0)
					$('#ad-location-iframe').attr('src',$('#ad-location-iframe').attr('src'));

					if($('#ad-pricing-iframe').length >0)
					$('#ad-pricing-iframe').attr('src',$('#ad-pricing-iframe').attr('src'));

					if($('#ad-position-iframe').length >0)
					$('#ad-position-iframe').attr('src',$('#ad-position-iframe').attr('src'));

					if($('#ad-category-iframe').length >0)
					$('#ad-category-iframe').attr('src',$('#ad-category-iframe').attr('src'));

					if($('#ad-device-iframe').length >0)
					$('#ad-device-iframe').attr('src',$('#ad-device-iframe').attr('src'));

					if($('#ad-time-iframe').length >0)
					$('#ad-time-iframe').attr('src',$('#ad-time-iframe').attr('src'));


					window.setTimeout(function(){window.location.reload();},20);
				}
				else
				{
					window.location.reload();
				}
			}
		});
	}
}

function LoadNotifications(notificationUrl)
{
	admvaluestring=$('#admvaluestring').val();

	if(admvaluestring !="")
	{
		var currentcookie=Get_Cookie('adm_content');

		if(currentcookie != null && currentcookie !="")
		currentcookie=currentcookie+'-'+admvaluestring;
		else
		currentcookie=admvaluestring;

		Set_Cookie('adm_content',currentcookie,20000,"/") ;

		$('#admvaluestring').val('');
	}

	window.location.href = notificationUrl;
}



var today = new Date();
today.setTime(today.getTime());
today.setHours(0);
today.setMinutes(0);
today.setSeconds(0);

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

function Set_Cookie( name, value, expires, path, domain, secure )
{
	if(expires)
	expires = expires * 1000 * 60 * 60 ;

	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name + "=" +escape( value ) + ";expires=" + expires_date.toUTCString()  + ( ( path ) ? ";path=" + path : "" ) + ( ( domain ) ? ";domain=" + domain : "" ) + ( ( secure ) ? ";secure" : "" );
}
function manageTabClicks(from, section, tabIndex, tabCount, direction, tabPricing = "", tabIframeID = "")
{
  //from => admin / user
  //section => passing action name

  $('.tab-btn').removeClass('active');
  $('.tab-btn-'+tabIndex).addClass('active');

	const tabWidth             = 100 / tabCount;
	const indicator            = document.getElementById("tabIndicator");

	if(direction == 1)
	indicator.style.right = `${tabIndex * tabWidth}%`;	
	else
  	indicator.style.left = `${tabIndex * tabWidth}%`;

	if(section == 'ad_report' || section == 'adcode_report')
	{
		$('.report-div').addClass("d-none");
		$('.graph-div-outer').addClass("d-none");

		$('.report-div-'+tabPricing).removeClass("d-none");
		$('.graph-div-outer-'+tabPricing).removeClass("d-none");

		var container = document.querySelector(`#traffic-graph-${tabPricing}`);

		if (!container.dataset.chartRendered)
		{
			trafficCharts[tabPricing].render();
			accountsCharts[tabPricing].render();
			container.dataset.chartRendered = "true";
		}
	}
	else if(section == 'advertiser_country' || section == 'publisher_country')
	{
		$('.country-map-outer').addClass("d-none");
		$('.country-map-outer-'+tabPricing).removeClass("d-none");

		google.charts.setOnLoadCallback(function () { drawRegionsMap(tabPricing); });
	}
	else if(section == 'top_ads' || section == 'top_adcodes')
	{
		$('.report-div').addClass("d-none");
		$('.report-div-'+tabPricing).removeClass("d-none");
	}
	else if(section == 'advertiser_statistics')
	{
		$('.report-div-tab').addClass("d-none");
		$('.report-div-tab-'+tabIndex).removeClass("d-none");
		$('#tab').val(tabIndex);

		//Need to verify
		if(document.forms['pagination1'])
		document.forms['pagination1'].tab.value = tabIndex;
	}
  else if(section == 'publisher_statistics' || section == 'ad_details' || section == 'adcode_details' || section == 'site_details' || section == 'affiliate_marketplace' || section == 'referral_statistics' || section == 'conversion_code' || section == 'cpd_marketplace'  )
  {
		$('.report-div-tab').addClass("d-none");
		$('.report-div-tab-'+tabIndex).removeClass("d-none");
		$('#tab').val(tabIndex);
	}

	if(tabIframeID)
	{
		var contentHeight = $(".body-section").outerHeight();
			contentHeight = parseFloat(contentHeight) + 10;

		$(tabIframeID, window.parent.document).css("height", contentHeight+"px");
  }
}
function resetSortIcons() {
    document.querySelectorAll(".sort-icons i").forEach(i => i.classList.remove("sort-selected"));
  }


$(document).ready(function() {
   (function()
   {  

	if($(".sortable").length > 0)
	{
	
	
	  document.querySelectorAll(".data_table td.sortable").forEach((cell, index) => {
	    const ascIcon = cell.querySelector(".fa-sort-asc");
	    const descIcon = cell.querySelector(".fa-sort-desc");    
	    const form = document.getElementById("report-filter");

	    if(ascIcon){
	      ascIcon.addEventListener("click", e => {
		e.stopPropagation();
		resetSortIcons();
		ascIcon.classList.add("sort-selected");

		const ascColumnKey = ascIcon.getAttribute("data-column"); 

		$("#sortBy").val(ascColumnKey);
		$("#orderBy").val("asc");
		form.submit();
	      });
	    }

	    if(descIcon){
	      descIcon.addEventListener("click", e => {
		e.stopPropagation();
		resetSortIcons();
		descIcon.classList.add("sort-selected");
		
		const descColumnKey = descIcon.getAttribute("data-column"); 

		$("#sortBy").val(descColumnKey);
		$("#orderBy").val("desc");
		form.submit();	
	      });
	    }
	  });
	}
   })();
});
