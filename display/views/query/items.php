<?php
//ob_start();

$pid=$this->get_variable('pid');
$display_type=$this->get_variable('display_type');
$pop_enabled=$this->get_variable('pop_enabled');
$referral_enabled=$this->get_variable('referral_enabled');
$adv_ref_enabled=$this->get_variable("adv_ref_enabled");
$pub_ref_enabled=$this->get_variable("pub_ref_enabled");
$native_enabled=$this->get_variable("native_enabled");
$expandable_enabled=$this->get_variable('expandable_enabled');
$skin_enabled=$this->get_variable('skin_enabled');
$popcode=intval($this->get_variable('popcode'));


$prid=intval($this->get_variable('prid'));
$popdirect=intval($this->get_variable('popdirect'));

$display_mouse_hover=Configuration::get_instance()->read('credit_text_display_mouse_hover');

$base_url=BASE;
$track_base_url=TRACK_BASE;


if(strpos($base_url, 'https') === 0)
$base_url=substr($base_url,6);
else
$base_url=substr($base_url,5);


if(strpos($track_base_url,'https') === 0) 
$track_base_url=substr($track_base_url,6);
else
$track_base_url=substr($track_base_url,5);


$bannerflag=0;
$passcontent="";
$expandableflag=0;





$credit_text="";
$credit_type=0;

$credit_icon="";
$credit_icon_type=0;



	$rid=0;
	$credit_link=BASE;

if($referral_enabled ==1 && $pid >0 && ($adv_ref_enabled ==1 || $pub_ref_enabled ==1))
$credit_link=BASE.'?rid='.$pid;


$aduid=$this->get_variable('aduid');
$max_title_length=Configuration::get_instance()->read('max_ad_title_length');
$max_desc_length=Configuration::get_instance()->read('max_ad_desc_length');
$max_url_length=Configuration::get_instance()->read('max_display_url_length');


if(($display_type ==9 || $popcode == 1) && $pop_enabled ==1)
{
	//header("Content-type:application/javascript");   //Need it for some server requests



	
    $kid=intval($this->get_variable('key_val'));
    $sid=intval($this->get_variable('sid'));
    
	
	$originalad=$this->get_variable('originalad');
	
	$pop_ad_strings=$this->get_variable('pop_ad_strings');
	$pop_aid=$this->get_variable('pop_aid');
	
	$poptype=intval($this->get_variable('poptype'));
	$click_url=$this->get_variable('click_url');
	
	
	
	$time=time();
	
	$impTime =date("Y",time());
	$impTime.=date("m",time());
	$impTime.=date("d",time());
	$impTime.=date("H",time());
	
	$impTimeMinute=$impTime.date("i",time());
	
	$clickid=$impTimeMinute.date("s",time()).rand(99,999);	

	
	
	$admarket_name	= Configuration::get_instance()->read('admarket_name');
	$cookie_exp	= Configuration::get_instance()->read('cpa_conversion_tracking_interval');
	$md5_value      = md5($pop_aid."-".$kid."-".$aduid."-".$sid."-".$cookie_exp."-".$clickid."-data-".$admarket_name);
	$click_url	= $click_url."?utm_campain=".$pop_aid."-".$kid."-".$aduid."-".$sid."-".$cookie_exp."-".$clickid."-".$md5_value."&utm_source=".$admarket_name;
	
	
	$pop_window_width=$this->get_variable('pop_window_width');
	$pop_window_height=$this->get_variable('pop_window_height');
	
	$day_limit=$this->get_variable('day_limit');
	
	
	if($popdirect ==0){
	
		
			if($poptype ==3 || $poptype ==5 || $poptype ==6 || $poptype ==7)
			$poptype=3;
			else if($poptype ==1 || $poptype ==4 || $poptype ==6 || $poptype ==7)
			$poptype=1;
			else if($poptype ==2 || $poptype ==4 || $poptype ==5 || $poptype ==7)
			$poptype=2;	
			else
			{
				if($originalad ==0 && $poptype ==0)
				$poptype=1;
			}		
	
	
?>	

windowwidth='<?php echo $pop_window_width;?>';
windowheight='<?php echo $pop_window_height;?>';
if(windowwidth==0 || windowwidth == '' )
{
 windowwidth= window.innerWidth;
}
if(windowheight==0 || windowheight == '' )
{
 windowheight= window.innerHeight ;
}




			var pop_click=0;

			var popEvent = function() 
			{
				 if(!pop_click)
				 {
				 	pop_click=1;
				 
				 
				 
	 <?php 
		$popcontent=array('operation'=>'popopen','auid'=>$aduid,'addelay'=>$day_limit);
		$popcontent=json_encode($popcontent);	 
     ?>
				 
	window.parent.postMessage('<?php echo $popcontent;?>',"*");
				 
			 
			 <?php if($poptype ==1){?>
			 window.open('<?php echo $this->make_url('query/load/'.$this->mybase64_encode($click_url).'/{POPPARAM}');?>','','width='+windowwidth+'px,height='+windowheight+'px'); //Up
			 <?php } else if($poptype ==2){?>
			 PopWindowUnder('<?php echo $this->make_url('query/load/'.$this->mybase64_encode($click_url).'/{POPPARAM}');?>',windowwidth,windowheight);	//Under
			 <?php } else if($poptype ==3){?>
			 window.open('<?php echo $this->make_url('query/load/'.$this->mybase64_encode($click_url).'/{POPPARAM}');?>');
			 <?php }?>
			 }
        };

        
        if(window.addEventListener)
        window.addEventListener("click",popEvent, false);
        else
        window.attachEvent("onclick",popEvent);
				
			

		function Set_Cookie( name, value, expires, path, domain, secure ) 
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

	
		
function PopWindowUnder(url,width,height) 
{
    var main_window  = (top != self && typeof(top.document.location.toString()) === 'string') ? top : self;

    var useragent = function() 
    {
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
	       try 
	         {
	            pop_object.blur();
	            pop_object.opener.window.focus();
	            window.self.window.focus();
	            window.focus();
	
				if(useragent.firefox) 
				closewindow(); 
	            else if(useragent.webkit) 
	            closetab();
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


    function closewindow() 
    {
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

	/*
        var clickevent = document.createEvent("MouseEvents");
        clickevent.initMouseEvent("click", false, true, window, 0, 0, 0, 0, 0, true, false, false, true, 0, null);
        newtab.dispatchEvent(clickevent);
        */

        newtab.parentNode.removeChild(newtab);

    	window.open(newtab.href).close();
    }

    PopWindow(url,width,height);
    
}
		
	
<?php }else{

	$this->click_url = $this->mybase64_encode($click_url);
	
/*	
	?>
<script data-cfasync="false" type="text/javascript">
var popEvent = function() 
{
	window.open('<?php echo $this->make_url('query/load/'.$this->mybase64_encode($click_url).'/{POPPARAM}');?>','_self');
};

if(window.addEventListener)
window.addEventListener("load",popEvent, false);
else
window.attachEvent("onload",popEvent);
</script>

<?php */
}
	
}
else if($display_type !=9 && $popcode !=1)
{


$cpm_ad_strings='';
$this->cache_cpm_string='';

$number=intval($this->get_variable('number'));
$no_of_ads=$number;
$number_balance=$this->get_variable('number_balance');

$adunittype=$this->get_variable('adunittype');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$video_enabled=$this->get_variable('video_enabled');

$interstitial_enabled=$this->get_variable('interstitial_enabled');
$cpa_enabled=$this->get_variable('cpa_enabled');
$textimage_enabled=$this->get_variable('textimage_enabled');
$ppctrack=$this->get_variable('ppctrack');
$cpatrack=$this->get_variable('cpatrack');
$cpmtrack=$this->get_variable('cpmtrack');
$htmltrack=$this->get_variable('htmltrack');
$sponsoredtrack=$this->get_variable('sponsoredtrack');
$interstitialtrack=$this->get_variable('interstitialtrack');


$expandable_width=$this->get_variable('expandable_width');
$expandable_height=$this->get_variable('expandable_height');
$expandable_type=$this->get_variable('expandable_type');
$expandable_style=$this->get_variable('expandable_style');




$direction=0;
$language='';

		
$bannerwidth=$this->get_variable('banner_width');
$bannerheight=$this->get_variable('banner_height');
$native=$this->get_variable('native');
$mem_obj=$this->memcache_connect();
if($mem_obj == false || ADUNIT_MEMCACHE_ENABLED == 0)
{
 	$res=$this->get_result('res');
 	$result=$res[0];
}
else 
{    
	$result=$this->get_array('res');
}	


if($native_enabled ==1 && $native ==1)
{
$nativead_minwidth_top_aligned=$this->get_variable('top_aligned_width');
$nativead_minheight_top_aligned=$this->get_variable('top_aligned_height');
$nativead_minwidth_left_aligned=$this->get_variable('left_aligned_width');
$nativead_minheight_left_aligned=$this->get_variable('left_aligned_height');
$layout=$result['layout'];
$rows=$this->get_variable('rows');
$columns=$this->get_variable('columns');
$ads_Count=$rows*$columns;
}


	
$player_width=0;
$player_height=0;

if($display_type ==13)
{
	$player_width  = $this->get_variable('player_width');
	$player_height = $this->get_variable('player_height');
}	





	$adcodeid=$result['auid'];
	$skipenabled=0;
	$skipposition=-1;
	$skipinterval=0;
	$addelay=0;
	$intercontent="";
	$intercontent1="";
	
	if($interstitial_enabled ==1 && $adunittype ==5)
	{
		$skipenabled=Configuration::get_instance()->read('display_skip_button');
		$skipposition=Configuration::get_instance()->read('skip_button_position');
		$skipinterval=Configuration::get_instance()->read('interstitial_skip_interval');
		$addelay=intval(Configuration::get_instance()->read('interstitial_interval_visitor'));
		
		
		$intercontent=array('operation'=>'open','auid'=>$adcodeid,'addelay'=>$addelay);
		$intercontent=json_encode($intercontent);

		$intercontent1=array('operation'=>'close','auid'=>$adcodeid,'addelay'=>$addelay);
		$intercontent1=json_encode($intercontent1);		
	}

	
	if($adunittype ==14)
	{	
		$container_id=$result['container_id']; 	
        $skin_positions=json_decode(html_entity_decode($result['skin_positions']),1);  //For set_result json content return as array, use html_entity_decode
		
        $skin_content['operation']='skin_display';
        $skin_content['auid']=$adcodeid;
        $skin_content['skin_positions']=$skin_positions;
        $skin_content['container_id']=$container_id;
        $skin_content['s_l_w']= Configuration::get_instance()->read('skin_left_width');
        $skin_content['s_r_w']= Configuration::get_instance()->read('skin_right_width');
        $skin_content['s_t_h']= Configuration::get_instance()->read('skin_top_height');
        $skin_content['s_b_h']= Configuration::get_instance()->read('skin_bottom_height');
        $skin_content['s_s_h']= Configuration::get_instance()->read('skin_side_height');    
	}	
	

	
if($number==0 && $display_type !=3)
{
	$show_iframe_space=intval(Configuration::get_instance()->read('show_iframe_space'));
	
	if($show_iframe_space ==0)
	{
		$noadscontent=array('operation'=>'noads','auid'=>$adcodeid);
		$noadscontent=json_encode($noadscontent);		
		
		?>
		<script data-cfasync="false" type="text/javascript">
		window.parent.postMessage('<?php echo $noadscontent;?>',"*");
		</script>		
		<?php 
	}
	exit;
}
else
{
	$originalcnts=$this->get_variable('originalcnts');
	$puboriginalcnts=$this->get_variable('puboriginalcnts');
	

	$sid=$this->get_variable('sid');
	$category_enabled=$this->get_variable('category_enabled');
	$sponsored_enabled=$this->get_variable('sponsored_enabled');
	$currentmapid=intval($this->get_variable('currentmapid'));
	
	
	if($interstitial_enabled ==1 && $adunittype ==5){?>
	<script data-cfasync="false" type="text/javascript">
	window.parent.postMessage('<?php echo $intercontent;?>',"*");
	</script>
	<?php }
	
	$custom_code='';
		
	
	
	
	
	
	
	if($adunittype !=14)
	{
	
	
	if($pid >0)
	{
		if($native==1)
		{
			
			$credit_text_id=Configuration::get_instance()->read('native_credit_text');
			
		
			if(Configuration::get_instance()->read('allow_brtype')==1)
			$adunit_border=$result['abr_type'];
			else 
			$adunit_border=Configuration::get_instance()->read('native_bordertype');
			
			
			if(Configuration::get_instance()->read('allow_tcolor')==1)
			$adunit_title_color=$result['at_color'];
			else
			$adunit_title_color=Configuration::get_instance()->read('native_tcolor');
			
			
			if(Configuration::get_instance()->read('allow_dcolor')==1)
			$adunit_desc_color=$result['ad_color'];
			else
			$adunit_desc_color=Configuration::get_instance()->read('native_dcolor');
			
			if(Configuration::get_instance()->read('allow_ucolor')==1)
			$adunit_url_color=$result['au_color'];
			else
			$adunit_url_color=Configuration::get_instance()->read('native_ucolor');
			
			
			if(Configuration::get_instance()->read('allow_ccolor')==1)
			$adunit_credit_color=$result['ac_color'];
			else
			$adunit_credit_color=Configuration::get_instance()->read('native_ccolor');
			
			if(Configuration::get_instance()->read('allow_bcolor')==1)
			$adunit_back_color=$result['ab_color'];
			else
			$adunit_back_color=Configuration::get_instance()->read('native_bcolor');
			
			
			if(Configuration::get_instance()->read('allow_brcolor')==1)
			$adunit_border_color=$result['abr_color'];
			else
			$adunit_border_color=Configuration::get_instance()->read('native_br_color');
			
		}
		else 
		{
			$credit_text_id=$result['credit_text'];
			
			
			if(Configuration::get_instance()->read('allow_brtype')==1)
			$adunit_border=$result['abr_type'];
			else 
			$adunit_border=$result['bordertype'];
			
			
			if(Configuration::get_instance()->read('allow_tcolor')==1)
			$adunit_title_color=$result['at_color'];
			else
			$adunit_title_color=$result['tcolor'];
			
			
			if(Configuration::get_instance()->read('allow_dcolor')==1)
			$adunit_desc_color=$result['ad_color'];
			else
			$adunit_desc_color=$result['dcolor'];
			
			if(Configuration::get_instance()->read('allow_ucolor')==1)
			$adunit_url_color=$result['au_color'];
			else
			$adunit_url_color=$result['ucolor'];
			
			
			if(Configuration::get_instance()->read('allow_ccolor')==1)
			$adunit_credit_color=$result['ac_color'];
			else
			$adunit_credit_color=$result['ccolor'];
			
			if(Configuration::get_instance()->read('allow_bcolor')==1)
			$adunit_back_color=$result['ab_color'];
			else
			$adunit_back_color=$result['bcolor'];
			
			
			if(Configuration::get_instance()->read('allow_brcolor')==1)
			$adunit_border_color=$result['abr_color'];
			else
			$adunit_border_color=$result['br_color'];
		}
	}
	else 
	{
		
		if($native==1)
		{		
			$credit_text_id=Configuration::get_instance()->read('native_credit_text');
			$adunit_border=Configuration::get_instance()->read('native_bordertype');
			$adunit_title_color=Configuration::get_instance()->read('native_tcolor');
			$adunit_desc_color=Configuration::get_instance()->read('native_dcolor');
			$adunit_url_color=Configuration::get_instance()->read('native_ucolor');
			$adunit_credit_color=Configuration::get_instance()->read('native_ccolor');
			$adunit_back_color=Configuration::get_instance()->read('native_bcolor');
			$adunit_border_color=Configuration::get_instance()->read('native_brcolor');
			
		}
		else
		{
			$credit_text_id=$result['credittext'];
			$adunit_border=$result['abr_type'];
			$adunit_title_color=$result['at_color'];
			$adunit_desc_color=$result['ad_color'];
			$adunit_url_color=$result['au_color'];
			$adunit_credit_color=$result['ac_color'];
			$adunit_back_color=$result['ab_color'];
			$adunit_border_color=$result['abr_color'];
		}
	}	
	

	
		if($native ==1)
		{
			$headerheight=30;
			
			$tlineheight=Configuration::get_instance()->read('native_tlineheight');
			$tfont=Configuration::get_instance()->read('native_tfont');
			$tsize=Configuration::get_instance()->read('native_tsize');
			$t_weight=Configuration::get_instance()->read('native_t_weight');
			$t_decoration=Configuration::get_instance()->read('native_t_decoration');
			$dlineheight=Configuration::get_instance()->read('native_dlineheight');
			$dfont=Configuration::get_instance()->read('native_dfont');
			$dsize=Configuration::get_instance()->read('native_dsize');
			$d_weight=Configuration::get_instance()->read('native_d_weight');
			$d_decoration=Configuration::get_instance()->read('native_d_decoration');
			$ulineheight=Configuration::get_instance()->read('native_ulineheight');
			$ufont=Configuration::get_instance()->read('native_ufont');
			$usize=Configuration::get_instance()->read('native_usize');
			$u_weight=Configuration::get_instance()->read('native_u_weight');
			$u_decoration=Configuration::get_instance()->read('native_u_decoration');
			$clineheight=Configuration::get_instance()->read('native_clineheight');
			$cfont=Configuration::get_instance()->read('native_cfont');
			$csize=Configuration::get_instance()->read('native_csize');
			$c_weight=Configuration::get_instance()->read('native_c_weight');
			$c_decoration=Configuration::get_instance()->read('native_c_decoration');
			$creditposition=Configuration::get_instance()->read('native_creditposition');
			$creditalignment=Configuration::get_instance()->read('native_creditalignment');
			$lineseperator=Configuration::get_instance()->read('native_lineseperator');
			$padding=Configuration::get_instance()->read('native_padding');
			$nativead_header_color_admin=Configuration::get_instance()->read('nativead_header_color');
			$nativead_header_admin=Configuration::get_instance()->read('nativead_header');
			$nativead_header_bgcolor_admin=Configuration::get_instance()->read('nativead_header_bgcolor');
			$nativetextad_minwidth=Configuration::get_instance()->read('nativetextad_minwidth');
			$nativetextad_minheight=Configuration::get_instance()->read('nativetextad_minheight');
			$txt_url_display=Configuration::get_instance()->read ("txt_url_display");
			$txt_desc_display=Configuration::get_instance()->read("txt_desc_display");
			$txtimg_url_display=Configuration::get_instance()->read("txtimg_url_display");
			$txtimg_desc_display=Configuration::get_instance()->read("txtimg_desc_display");
			
			$responsive=$result['responsive'];
			$custom_code=$result['custom_code'];
			$bannersize=$result['nativeimg_dimension'];
			$image_position =$result['nativeimg_position'];
			$nativead_header=$result['name'];
			$nativead_header_color=$result['htxt_color'];
			$nativead_header_bgcolor=$result['htxt_bgcolor'];
			
			if($adunittype ==4)
			{
				if($image_position ==0)
				{
					
					$singlewidth=$nativead_minwidth_left_aligned-($padding*2);
					$singlewidth1=$nativead_minwidth_left_aligned-($padding*2);
						
					$blockwidth=$nativead_minwidth_left_aligned*$columns;
					
		
					$singleheight=$nativead_minheight_left_aligned-($padding*2);
					$singleheight1=$nativead_minheight_left_aligned-($padding*2);
						
					$blockheight=$nativead_minheight_left_aligned*$rows;					
				}
				else 
				{
					$singlewidth=$nativead_minwidth_top_aligned-($padding*2);
					$singlewidth1=$nativead_minwidth_top_aligned-($padding*2);
						
					$blockwidth=$nativead_minwidth_top_aligned*$columns;
					
					$singleheight=$nativead_minheight_top_aligned-($padding*2);
					$singleheight1=$nativead_minheight_top_aligned-($padding*2);
						
					$blockheight=$nativead_minheight_top_aligned*$rows;					
				}
			}
			else
			{
				$singlewidth=$nativetextad_minwidth-($padding*2);
				$singlewidth1=$nativetextad_minwidth-($padding*2);
				
				$singleheight=$nativetextad_minheight-($padding*2);
				$singleheight1=$nativetextad_minheight-($padding*2);
				
				$blockwidth=$nativetextad_minwidth*$columns;
				$blockheight=$nativetextad_minheight*$rows;				
			}
			
			
			$blockwidth=$blockwidth;
			$blockheight=$blockheight+(30+($padding*2)); //For headline text			
		}
		else 
		{
			$tlineheight=$result['tlineheight'];
			$tfont=$result['tfont'];
			$tsize=$result['tsize'];
			$t_weight=$result['t_weight'];
			$t_decoration=$result['t_decoration'];
			$dlineheight=$result['dlineheight'];
			$dfont=$result['dfont'];
			$dsize=$result['dsize'];
			$d_weight=$result['d_weight'];
			$d_decoration=$result['d_decoration'];
			$ulineheight=$result['ulineheight'];
			$ufont=$result['ufont'];
			$usize=$result['usize'];
			$u_weight=$result['u_weight'];
			$u_decoration=$result['u_decoration'];
			$clineheight=$result['clineheight'];
			$cfont=$result['cfont'];
			$csize=$result['csize'];
			$c_weight=$result['c_weight'];
			$c_decoration=$result['c_decoration'];
			$creditposition=$result['creditposition'];
			$creditalignment=$result['creditalignment'];
			$lineseperator=$result['lineseperator'];
			$image_position=$result['image_position'];
		}
		
		
		if($credit_text_id > 0)
		{
			$credit_text_array=$this->get_credittext($credit_text_id,1,1);
			
			$credit_text=$credit_text_array[0];
			$credit_type=$credit_text_array[1];
			$credit_icon=$credit_text_array[2];
			$credit_icon_type=$credit_text_array[3];
		}		
?>



<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width, height=device-height" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo DEFAULT_CHARSET; ?>" />
<meta name="robots" content="noindex,nofollow" />

<script data-cfasync="false" type="text/javascript" src="js/jquery.min.js"></script>
<title></title>
<style type="text/css">

body{
padding:0;
margin:0;
}

a
{
	outline: none;
}

<?php if($direction ==1){?>
html{direction: rtl;}
body{direction: rtl;}

.market_main_outer
{
	overflow: hidden;
}
<?php }?>


<?php if($display_type ==13){?>
.video-link
{
	position: absolute;font-size: 12px;background-color: #EEEEEE;padding: 0 5px;
	top: 1px;
	left: 1px;
}

.video-link a
{
	color: #000000;text-decoration: none;outline: none;
}

<?php }?>


.market_main
{



<?php if($native ==1){
	
	if($responsive ==0){?>
	min-width:<?php  echo $blockwidth; ?>px;
	min-height:<?php  echo $blockheight; ?>px;
	<?php } 
	
	
if($adunit_border !=2){?>
border:1px solid <?php  echo $adunit_border_color; ?>;
<?php
}

	if($adunit_border==0) {?>
	   -moz-border-radius: 10px 10px 10px 10px;
	   
	   
	    border-top-left-radius: 10px;	
		border-top-right-radius: 10px;	
		border-bottom-left-radius: 10px;	
		border-bottom-right-radius: 10px;	
	<?php
	}	
} else if($adunittype==2 || $adunittype ==5) {?>
	border-width: 0px;
	width:<?php  echo $result['width']; ?>px;
    height:<?php  echo $result['height']; ?>px; 

<?php } else if($display_type ==13){?>

	border-width: 0px;
	width:<?php  echo $player_width; ?>px;
    height:<?php  echo $player_height; ?>px; 

<?php } else { ?>
   width:<?php  echo $result['width']-2; ?>px;
   height:<?php  echo $result['height']-2; ?>px; 
   border:1px solid <?php  echo $adunit_border_color; ?>;
<?php 
if($adunit_border==0) 
{?>
   -moz-border-radius: 10px 10px 10px 10px;
   
   
    border-top-left-radius: 10px;	
	border-top-right-radius: 10px;	
	border-bottom-left-radius: 10px;	
	border-bottom-right-radius: 10px;	
<?php
}	
} ?>

background-color: <?php if($adunittype==2 || $adunittype ==5) echo "#FFFFFF"; else echo  $adunit_back_color; ?>;

padding:0 0;
margin:0 0;
table-layout:fixed;
overflow:hidden;

}




<?php if($adunittype !=2 && $adunittype !=5){?>
.title a:link,.title a:visited,.title a:hover,.title a:active,.title a:focus
		{
		padding:5px;
		line-height:<?php echo $tlineheight;?>px;
		font-family:<?php echo $tfont;?>;
		font-size:<?php echo $tsize;?>;
		color:<?php echo $adunit_title_color;?>;
		
		<?php
		if($t_weight==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($t_decoration==3)
		$tdeco="blink";
		elseif($t_decoration==1)
		$tdeco="none";	
		else
		$tdeco="underline";		
		?>
		text-decoration:<?php echo $tdeco; ?>;
		word-break: break-word;
		}
		
.description
		{
		word-break: break-word;
		padding:5px;
		line-height:<?php echo $dlineheight;?>px;
		font-family:<?php echo $dfont;?>;
		font-size:<?php echo $dsize;?>;
		color:<?php echo $adunit_desc_color;?>;
		<?php
		if($d_weight==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($d_decoration==3)
		$ddeco="blink";
		elseif($d_decoration==1)
		$ddeco="none";	
		else
		$ddeco="underline";		
		?>
		text-decoration:<?php echo $ddeco; ?>;
		
		}
		
		
.url a:link,.url a:visited,.url a:hover,.url a:active,.url a:focus
		{
		word-break: break-word;
		padding:5px;
		line-height:<?php echo $ulineheight;?>px;
		
		font-family:<?php echo $ufont;?>;
		font-size:<?php echo $usize;?>;
		color:<?php echo $adunit_url_color;?>;
		<?php
		if($u_weight==2)
		$weight="bold";
		else
		$weight="normal";	
		?>
		font-weight:<?php echo $weight; ?>;
		<?php 
		if($u_decoration==3)
		$udeco="blink";
		elseif($u_decoration==1)
		$udeco="none";	
		else
		$udeco="underline";		
		?>
		text-decoration:<?php echo $udeco; ?>;
		white-space:nowrap;
		}


<?php } ?>


.inner_dv1
{
	float: left; 
 	display: inline-block;
}


.credit_market_first
{
height: <?php echo $clineheight; ?>px;
width: <?php echo $clineheight; ?>px;

<?php if($adunittype !=2 && $adunittype !=5 && $adunit_border ==0) {?>

-moz-border-radius: <?php echo $clineheight/2; ?>px <?php echo $clineheight/2; ?>px <?php echo $clineheight/2; ?>px <?php echo $clineheight/2; ?>px;


    border-top-left-radius: <?php echo $clineheight/2; ?>px;	
	border-top-right-radius: <?php echo $clineheight/2; ?>px;
	border-bottom-left-radius: <?php echo $clineheight/2; ?>px;	
	border-bottom-right-radius: <?php echo $clineheight/2; ?>px;

<?php } ?>
font-size:<?php echo $csize; ?>px;

<?php
if($c_weight==2)
$cweight="bold";
else
$cweight="normal";	
?>
font-weight:<?php echo $cweight; ?>;
background-color:<?php echo $adunit_border_color; ?>;
color:<?php echo $adunit_credit_color; ?>;
text-align:center;
vertical-align:middle;

<?php

if($creditposition==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($creditposition==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($creditalignment==1)  // Right
{
?>
right:0px;
<?php
}
?>
<?php
if($creditalignment==0)  // Left
{
?>
left:0px;
<?php
}
?>

z-index: 10001;
}
.credit_market_second
{
overflow:hidden;
white-space:nowrap;
<?php if($native ==0) {?>
max-width:<?php echo $result['width']-10; ?>px;

<?php }
if($credit_type ==0){?>
height: <?php echo $clineheight; ?>px;
background-color:<?php echo $adunit_border_color; ?>;
padding:0 5;
<?php }?>



margin:0px;

<?php
if($creditalignment==0)
$align="left";
else
$align="right";	
?>
text-align:<?php echo $align; ?>;

<?php
if($creditposition==1)  // Top
{
?>
position:absolute;
top:0px;
<?php
}
?>
<?php
if($creditposition==0)  // Bottom
{
?>
position:absolute;
bottom:0px;
<?php
}
?>
<?php
if($creditalignment==1)  // Right
{
?>
right:0px;

<?php if($adunittype !=2 && $adunittype !=5 && $adunit_border==0 && $result['creditposition']==0){?>
		
	-moz-border-radius: 10px 0px 10px 0px;

    border-top-left-radius: 10px;	
	border-top-right-radius: 0px;	
	border-bottom-right-radius: 10px;	
	border-bottom-left-radius: 0px;	
		
	<?php }	else if($adunittype !=2 && $adunittype !=5 && $adunit_border==0 && $result['creditposition']==1){?>
	
	-moz-border-radius: 0px 10px 0px 10px;
   
    border-top-left-radius: 0px;	
	border-top-right-radius: 10px;	
	border-bottom-right-radius: 0px;	
	border-bottom-left-radius: 10px;	
		
	<?php
	}	


}
?>
<?php
if($creditalignment==0)  // Left
{
?>
left:0px;


<?php if($adunittype !=2 && $adunittype !=5 && $adunit_border==0 && $creditposition==0) {?>
		
	-moz-border-radius: 0px 10px 0px 10px;
   
    border-top-left-radius: 0px;	
	border-top-right-radius: 10px;	
	border-bottom-right-radius: 0px;	
	border-bottom-left-radius: 10px;	
		
	<?php }	else if($adunittype !=2 && $adunittype !=5 && $adunit_border==0 && $creditposition==1) {?>
		
	-moz-border-radius: 10px 0px 10px 0px;
   
    border-top-left-radius: 10px;	
	border-top-right-radius: 0px;	
	border-bottom-right-radius: 10px;	
	border-bottom-left-radius: 0px;	
	
	<?php
	}	
}
?>


z-index: 10002;
}


.credit_market_second a:link,.credit_market_second a:visited,.credit_market_second a:hover,.credit_market_second a:active,.credit_market_second a:focus
{
color:<?php echo $adunit_credit_color; ?>;
font-family:<?php echo $cfont;?>;
font-size:<?php echo $csize;?>;

<?php
if($c_weight==2)
$weight="bold";
else
$weight="normal";	
?>
font-weight:<?php echo $weight; ?>;
<?php 
if($c_decoration==3)
$cdeco="blink";
elseif($c_decoration==1)
$cdeco="none";	
else
$cdeco="underline";		
?>
text-decoration:<?php echo $cdeco; ?>;
}


.inner_dv1
{
float: left; 
}


.image-box
{
	background-color: #CCCCCC;
	margin: 5px;
}

.skip-div
{

position: absolute;

<?php if($skipposition ==0){?>
top: 0px;
left:0px; 
<?php }else if($skipposition ==1){?>
top: 0px;
right:0px; 
<?php }else if($skipposition ==2){?>
bottom: 0px;
right:0px; 
<?php }else if($skipposition ==3){?>
bottom: 0px;
left:0px; 
<?php }?>


}

.skip-button
{
float:left;
cursor: pointer;
background:url(../images/skipad.png);
height: 30px;
width: 100px;
}

.skip-counter
{
	<?php if($skipposition ==0 || $skipposition ==3){?>
	float:right;
	<?php }else{?>
	float:left;
	<?php }?>
	border: 1px solid #000000;
	background-color:#000000;
	color: #FFFFFF;
	min-width:15px;
	padding: 4.5px 5px;
	text-align: center;';
}

.adv-here
{
	text-align: center;
	display: table-cell;
	vertical-align: middle;
	background-color: #EEEEEE;
}

.adv-here a
{
  	text-decoration: none;
  	color: #FFFFFF;
	background-color: #DC0000;
	padding: 5px;
}


<?php if($ecommerce_enabled ==1){	?>
	
.dummybutton {box-sizing: border-box !important;white-space: nowrap;border-radius: 2px;}
.dummyprice {vertical-align: middle;white-space: nowrap;}
.dummyofferprice {vertical-align: middle;white-space: nowrap;}


.display-outer-style {

   width:<?php  echo $result['width']; ?>px;
   height:<?php  echo $result['height']; ?>px; 
   border:1px solid;
   <?php if($adunit_border==0) {?>
   -moz-border-radius: 10px 10px 10px 10px;
   
   border-top-left-radius: 10px;	
   border-top-right-radius: 10px;	
   border-bottom-left-radius: 10px;	
   border-bottom-right-radius: 10px;	
   <?php } ?>

   box-sizing: border-box !important;overflow: hidden;
   }


.slidesection-style {text-align: center;float: left;box-sizing: border-box !important;position: relative;overflow: hidden;}
.slidesection-style::before {content: " ";display: inline-block;vertical-align: middle; }


.headlinesection-style {text-align: center;float: left;box-sizing: border-box !important;white-space:nowrap;}
.headlinesection-style::before {content: " ";display: inline-block;vertical-align: middle; }


.logosection-style {text-align: center;float: left;box-sizing: border-box !important;}
.logosection-style::before {content: " ";display: inline-block;vertical-align: middle;}



.singleadsection-style
{

border:1px solid;


<?php if($adunit_border==0) {?>
   -moz-border-radius: 10px 10px 10px 10px;
   
    border-top-left-radius: 10px;	
	border-top-right-radius: 10px;	
	border-bottom-left-radius: 10px;	
	border-bottom-right-radius: 10px;	
<?php }	?>

text-align: center;float: left;box-sizing: border-box !important;overflow: hidden;
}

.singleadsection-style::before {content: " ";display: inline-block;vertical-align: middle; }

.slideinner-style {box-sizing: border-box !important;left: 0px;position: relative;overflow: hidden;display: inline-block;vertical-align: middle;}

.slideblock-style{float: left;box-sizing: border-box !important;vertical-align: middle;}
.slideblock-style::before {content: " ";display: inline-block;vertical-align: middle; }

.slideblockinner-style {display: inline-block;vertical-align: middle;}



.slideleft-style {height: 100%;box-sizing: border-box !important;position: absolute;left: 0px;z-index: 1000;}
.slideleft-style::before {content: " ";display: inline-block;vertical-align: middle;height: 100%; }

.slideright-style {height: 100%;box-sizing: border-box !important;position: absolute;right: 0px;z-index: 1000;}
.slideright-style::before {content: " ";display: inline-block;vertical-align: middle;height: 100%; }




.slideleftinner-style,.sliderightinner-style 
{
padding: 0px 1px 1px 2px;
cursor: pointer !important;
border:1px solid;
border-radius: 5px;
font-weight: bold;
font-size: 14px;
}


.slideinnerholder-style{height: 100%;float: left;box-sizing: border-box !important;overflow: hidden;}

.slideleftinner-style{display: none;}

.preview-display-style {width: 100%;height: 100%;text-align: left;box-sizing: border-box !important;}

.ecommerceurl a:link,.ecommerceurl a:visited,.ecommerceurl a:hover,.ecommerceurl a:active,.ecommerceurl a:focus
{
	white-space:nowrap;
}


.span-button-preview {border-radius: 2px;}

.display-style-right {text-align: right;}



<?php }?>

</style>
<?php  echo html_entity_decode($custom_code);?>

</head>
<body>

<?php 

 	
 	
if($native ==1)
{

	
  $result['lineseperator']=0;
  
  if($nativead_header =='')
  $nativead_header=$nativead_header_admin;
  
  if($nativead_header_color=='')
  $nativead_header_color=$nativead_header_color_admin;
  
  if($nativead_header_bgcolor=='')
  $nativead_header_bgcolor=$nativead_header_bgcolor_admin;
  
  $dir2='ltr';
  
  if($direction==1)
  $dir2='rtl';
  
  $dir=$dir2;
?>


	<div class="market_main"  id="market_main_<?php echo $aduid;?>">
	<div style="height: 30px;"><h4 style="direction:<?php echo $dir2;?>;color: <?php echo $nativead_header_color;?>;background-color:<?php echo $nativead_header_bgcolor;?>;padding:<?php echo $padding;?>;margin-top: 0px;margin-bottom: 0px;"><?php echo $nativead_header;?></h4></div>
	
<?php } else {?>	
<div class="market_main" > 
<?php }?>

<?php if($credit_text != ""){?>  
<div id="cimdv" class="credit_market_first" onMouseOver="AdmarketCreditLoad()"><?php echo $credit_icon;?></div>
<div class="credit_market_second" id="cdv" style="display:none;" onMouseOut="AdmarketCreditDisable()" >
<a target="_blank"  href="<?php echo $credit_link;?>"><?php echo $credit_text;?></a>
</div>
<?php }	?>

<?php }?>





<?php 
	$client_ip=$this->get_variable('client_ip');	
	
	

	if($number >0)	
	{	
	   $i=0;
	   $singlewidth=0;
	   $singleheight=0;	
	   $totalnumber=$number;
	   
	   
	   if($adunittype !=2 && $adunittype !=5 && $adunittype !=14 && $native ==0)  //Image & Interstitial
	   {
		   if($result['orientaion']==2)  //horizontal	   
		   {
			   $singlewidth=$result['width']/$totalnumber;
			 
			   if($result['lineseperator']==1)
			   $singlewidth=$singlewidth-($totalnumber+1);
			   else
			   $singlewidth=$singlewidth-2;
		   }
		   else    //vertical	   
		   {
			   $singleheight=$result['height']/$totalnumber;
				
			   if($result['lineseperator']==1)
			   $singleheight=$singleheight-($totalnumber+1);
			   else
			   $singleheight=$singleheight-2;	 
		   }
	   }

	   if($originalcnts >0) 
	   {
		 	$res1=$this->get_result('res1');
		 	
		 	if($number_balance >0)
			{
			 	$res1balance=$this->get_result('res1balance');
		
			 	foreach($res1balance as $key123=>$value123)
			 	{
			 		$res1[]=$value123;
			 	}
			}	 	
		 	
			
		 	$cpm_profit_percentage=0;
		 	if($display_type ==1)
		 	{
		 		$specific_profit=$this->get_variable('specific_profit');
		 		
		 		if($specific_profit >0)
		 		$cpm_profit_percentage=$specific_profit;
		 		else
		 		$cpm_profit_percentage=Configuration::get_instance()->read('cpm_profit_percentage');
		 	}
		 	
		 	
	   		$cpv_profit_percentage=0;
		 	if($display_type ==13)
		 	{
		 		$specific_profit=$this->get_variable('specific_profit_cpv');
		 		
		 		if($specific_profit >0)
		 		$cpv_profit_percentage=$specific_profit;
		 		else
		 		$cpv_profit_percentage=Configuration::get_instance()->read('cpv_profit_percentage');
		 	}		 	
		 	
		 	 
		 	
	 
			foreach($res1 as $key=>$result1)
			{
			 	$clksurl='';
			 	$kid_data=0;

			 	if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6 || $display_type ==13)
			 	{
			 		if($display_type ==3)
			 		$kid_data=$currentmapid;
			 		else
			 		$kid_data=$result1['kid'];
			 		
			 		
				    if($i==0 && $adunittype !=14)
				    {
				    	$bs=md5(rand(0,10));
				    	
				    	if($adunittype ==2 || $adunittype ==5 || $adunittype ==13)
				    	{?>
				    	<a style="display: none;" target="_blank" href="<?php echo $track_base_url.TRACK_DIR.'/index.php?page=click/validate/'.$result1['aid'].'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/{ENCIP}/'.$bs.'/0/'.$result1['retarget'];?>"><img style="height:<?php echo $result['height'];?>px;width:<?php echo $result['width'];?>px;" src="<?php echo BASE.'images/data.png';?>" border="0"></a>
				    	<?php } else { ?>
				    	<div   style=" display: none;width: 0px; ">
				    	<div class="title"><a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/validate/'.$result1['aid'].'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/{ENCIP}/'.$bs.'/0/'.$result1['retarget']; ?>" target="_blank"><?php echo $result1['title']; ?></a></div>
				    	<div class="description"><?php echo $result1['description']; ?></div>
				    	<div class="url"><a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/validate/'.$result1['aid'].'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/{ENCIP}/'.$bs.'/0/'.$result1['retarget']; ?>" target="_blank"><?php 	echo $result1['display_url'];	?></a></div>
				    	</div>
				    <?php 	
				    	}
				    }	
			 	
				 	$bs=md5($result1['aid'].$kid_data.$result['auid'].$sid.$result1['retarget']);
				 	
				 	$clksurl=$track_base_url.TRACK_DIR.'/index.php?page=click/validate/'.$result1['aid'].'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/{ENCIP}/'.$bs.'/0/'.$result1['retarget'];
				 	$clks_gallery=TRACK_BASE.TRACK_DIR.'/index.php?page=click/validate/'.$result1['aid'].'/'.$kid_data.'/'.$result['auid'].'/'.$sid.'/{ENCIP}/'.$bs.'/0/'.$result1['retarget'];
				 	
			 	}

			 	
			 	$profit=0;
			 	$singleimprate=0;
			 	
			 	if($display_type ==1)
			 	{
					$singleimprate=$result1['default_rate']/1000;
			 		
			 		if($pid >0)
			 		{
			 			if($result1['dsp'] ==2)
						$profit=$singleimprate;
						else 
	 					$profit=$singleimprate*$cpm_profit_percentage/100;
			 		}
			 		else
			 		$profit=$singleimprate;
			 	}
				
			 	
				if($display_type ==13)
			 	{
					$singleimprate=$result1['default_rate'];
			 		
			 		if($pid >0)
 					$profit=$singleimprate*$cpv_profit_percentage/100;
 					else
			 		$profit=$singleimprate;
			 	}			 	
				

			if($native ==1)	{?>
			<div class="inner_dv1 inner_cap" id="innerdv_<?php echo $result1['aid'];?>_<?php echo $aduid;?>" 
			style="<?php if($responsive ==0 && $i == $columns){?> clear:both; <?php } if($responsive ==1){?>min-height: <?php echo $singleheight1; ?>px; min-width: <?php echo $singlewidth1;?>px;<?php } else {?>height: <?php echo $singleheight1;  ?>px;width: <?php echo $singlewidth1; ?>px;padding: <?php echo $padding;?>px;<?php }if($result['lineseperator']==1 && $i < $totalnumber-1 ){ ?>border-right:solid 1px <?php echo $result['br_color']; ?>; <?php } ?>direction:<?php echo $dir;?>">
			
			<table style="width: 100%;">
			
			<?php if($adunittype ==4 && $image_position ==1){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			<tr>
			<?php if($adunittype ==4 && $image_position ==0){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			<td <?php if($adunittype ==4 && $image_position ==1 ){?> style="text-align: center;" <?php }?>>
			
		
			<div class="title"><a href="<?php  echo  $clksurl; ?>" target="_blank" style="padding-left:0px;"><?php echo $result1['title']; ?></a></div>
			<?php if(($adunittype==4 && $txtimg_desc_display==1) ||($adunittype==1 && $txt_desc_display==1)) {?>
			
			<div class="description" style="padding-left:0px;"><?php echo $result1['description']; ?></div>
			<?php } if(($adunittype==4 && $txtimg_url_display==1) ||($adunittype==1 && $txt_url_display==1)){?>
			<div class="url"><a href="<?php  echo  $clksurl; ?>" target="_blank" style="padding-left:0px;"><?php echo $result1['display_url'];?></a></div>
			<?php }?>
			</td>

			</table>
			</div>	
			<?php } else if($adunittype ==13) {
			
				$controls_enabled=Configuration::get_instance()->read('html5_video_controls_enabled');
				$autoplay_enabled=Configuration::get_instance()->read('html5_video_autoplay_enabled');
				$cpv_interval=intval(Configuration::get_instance()->read('html5_player_impression_tracking_interval'));
				$video_muted=Configuration::get_instance()->read('html5_video_mute_enabled');
				
				
				?>
			 	
			<video width="<?php echo $player_width;?>" height="<?php echo $player_height;?>" <?php if($video_muted ==1){?> muted <?php }?> <?php if($controls_enabled ==1){?>controls<?php }?> <?php if($autoplay_enabled ==1){?>autoplay<?php }?> onplay="TrackData(1,<?php echo intval($result1['duration_seconds']);?>,<?php echo $cpv_interval;?>);" onpause="TrackData(2,<?php echo intval($result1['duration_seconds']);?>,<?php echo $cpv_interval;?>);">
			<source src="<?php echo $base_url.DATA_DIR.'/video/'.$result1['aid'].'/'.$result1['banner'];?>" type="<?php echo $result1['mime_type'];?>"></source>
			<?php echo 'Your browser does not support HTML5 video';?>
			</video>			 	
			 	
			<div class="video-link"><a target="_blank" href="<?php echo $clksurl;?>"><?php echo $result1['display_url'];?></a></div>	
			 	
			<?php }else if($adunittype ==14){	
        
	        $skin_banners=array();
	        $skin_height=array();
	        $banner_list=json_decode(html_entity_decode($result1['banner']),1);
	           
	        foreach($banner_list as $key => $value) 
	        {
	        	$image_array=explode('_',$value);
	        	
	            $skin_key=$image_array[0];  //Width of skin banner
	
	        	$skin_banners[$skin_key]=$base_url.DATA_DIR.'/'.$result1['aid'].'/'.$value;
	        	$skin_height[$skin_key]=$image_array[1];
	        }
	        
	        $skin_content['click_url']=$clksurl;
	        $skin_content['skin_banners']=$skin_banners;
			$skin_content['skin_height']=$skin_height;
	        
	        $skin_content=json_encode($skin_content);
	        

        	?>
		        <script data-cfasync="false" type="text/javascript">
		        window.parent.postMessage('<?php echo $skin_content;?>',"*");
		        </script>
			<?php 
			
			
			} else if($adunittype==2 || $adunittype==5)	{
				if($result1['dsp'] ==2)
				echo $result1['description'];
				else if($result1['type'] ==5){?>
				<script data-cfasync="false" type="text/javascript">

				var skipinter='<?php echo $skipinterval;?>';
				var skipinterval=window.setInterval(function(){

					skipinter=skipinter-1;
					document.getElementById('skip-counter').innerHTML=skipinter;
				
					if(skipinter <=0)
					{
						window.clearInterval(skipinterval);
						window.parent.postMessage('<?php echo $intercontent1;?>',"*");
					}
				}, 1000);
				
				function CloseSkip()
				{
					window.clearInterval(skipinterval);
					window.parent.postMessage('<?php echo $intercontent1;?>',"*");
				}
				
				function CloseSkipAd()
				{
					skipinter=0;
				}
				</script>
				
				<span class="skip-div">
				<div class="skip-counter" id="skip-counter"><?php echo $skipinterval;?></div>
				
				<?php if($skipenabled ==1){?>
				<span class="skip-button" onclick="CloseSkip();"></span>
				<?php }?>
				</span>
				
				<a target="_blank" href="<?php  echo $clksurl; ?>" onclick="CloseSkipAd();">
				<img style="height:<?php echo $result['height']; ?>;width:<?php echo $result['width']; ?>;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" >
				</a>
				<?php } else {
					
					if($result1['type'] ==7){
					echo $this->get_ad_display_preview($result1['aid'],$clks_gallery,$result1['retargetid']);
					} else {	

	
						
					$bannerflag=1;	
						
					$expandableflag=0;
					if($expandable_enabled ==1 && $result1['expandable'] ==1 && $result1['expandable_banner'] !="" && $expandable_width >0 && $expandable_height >0)
					$expandableflag=1;
								
					$bannerpath="";
					$passcontent="";
					$passcontent1="";
					
					
					
					
						
					if($expandableflag ==1)
					{
						$bannerpath=$base_url.DATA_DIR."/".$result1['aid']."_exp_".$result1['expandable_banner'];
						
						$passcontent=array('operation'=>'expandable_configuration','expandable'=>1,'auid'=>$adcodeid,'bannerpath'=>$this->mybase64_encode(trim($bannerpath)),'width'=>$expandable_width,'height'=>$expandable_height,'clksurl'=>$clksurl,'style'=>$expandable_style);
						$passcontent=json_encode($passcontent);
						
						$passcontent1=array('operation'=>'expandable_open','expandable'=>2,'auid'=>$adcodeid,'width'=>$expandable_width,'height'=>$expandable_height,'expandable_type'=>$expandable_type,'style'=>$expandable_style);
						$passcontent1=json_encode($passcontent1);
					}
					
					

					$js_function="";
								
					if($expandableflag ==1 && $expandable_type ==0)
					$js_function=' onclick="LoadExpandableData(0);" ';
								
					if($expandableflag ==1 && $expandable_type ==1)
					$js_function=' onmouseover="LoadExpandableData(0);" ';
					
	
					
					?>
					
				<a <?php if($expandableflag ==0 || $expandable_type ==1){?> target="_blank" <?php }?> <?php if($expandableflag ==0 || ($expandableflag ==1 && $expandable_type ==1)){?>  href="<?php echo $clksurl;?>" <?php }?> <?php echo $js_function;?> >
				<img style="height:<?php echo $result['height']; ?>;width:<?php echo $result['width']; ?>;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" />
				</a>
				
				<?php }}			
			}
			else
			{?>
			<div class="inner_dv1" style="height: <?php if($result['orientaion']==2){echo $result['height'];}else {echo $singleheight;} ?>px;   width: <?php if($result['orientaion'] == 2){echo $singlewidth;}else {echo $result['width'];} ?>px;<?php     if($result['lineseperator']==1 && $i < $totalnumber-1){ if($result['orientaion'] == 2){?>border-right:solid 1px <?php echo $result['br_color'];?>;<?php }else{?>border-bottom:solid 1px <?php echo $result['br_color'];?>;<?php }}?>overflow: hidden;">
			
			<table style="width: 100%;">
			
			<?php if($adunittype ==4 && $result['image_position'] ==1){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			<tr>
			<?php if($adunittype ==4 && $result['image_position'] ==0){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			<td <?php if($adunittype ==4 && ($result['image_position'] ==1 || $result['image_position'] ==3)){?> style="text-align: center;" <?php }?>>
			
		
			<div class="title"><a href="<?php  echo  $clksurl; ?>" target="_blank"><?php echo $result1['title']; ?></a></div>
			<div class="description"><?php echo $result1['description']; ?></div>
			<div class="url"><a href="<?php  echo  $clksurl; ?>" target="_blank"><?php echo $result1['display_url'];?></a></div>
			</td>
			
			
			<?php if($adunittype ==4 && $result['image_position'] ==2){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			
			</tr>
			
			<?php if($adunittype ==4 && $result['image_position'] ==3){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo  $clksurl; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $result1['aid'].'_'.$result1['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			
			</table>
			</div>	
			<?php }?>
			
			<?php

$rid=0;
			
				if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6 || $display_type ==13)
				{
					if($cpm_ad_strings !='')
					$cpm_ad_strings.='.data.';		

					
					$dsp=$display_type;
					
					if($display_type ==1 || $display_type ==13)
					{
						$dsp=$result1['dsp'];
						
						
						
						if($display_type == 1 && $dsp == 6)   // For first time, CPA ad display as CPM (Custom)
						$dsp = 1;
						
						
					 	if($dsp !=2 && $referral_enabled ==1 && $adv_ref_enabled ==1)
					 	{
					 		if($result1['refferal_status'] == 1)
							$rid=intval($result1['rid']);
	
					 	}
					}
					
					
					$key_id=0;
					
					if($display_type !=3)
					$key_id=$result1['keyid'];
					
					
				    $cpm_ad_strings.=$result1['userid'].'|'.$result1['aid'].'|'.$kid_data.'|'.$pid.'|'.$result['auid'].'|1|'.$sid.'|'.$dsp.'|'.$key_id;
					
				    
				    $mapid=0;
				    
				   
				    $mapid=$result1['aid'];
				    
					if($display_type ==1 || $display_type ==13)
					$cpm_ad_strings.='|'.$mapid.'|'.$profit.'|'.$singleimprate.'|'.$rid.'|'.$prid;
				}
				
				$i++;
			}
	 
			if($i >0)
			{
				if($display_type ==0 || $display_type ==1 || $display_type ==3 || $display_type ==6 || $display_type ==13)
				{
					$this->cache_cpm_string=$cpm_ad_strings;
					
					$tracking_interval=0;
					
					if($display_type ==0)
					$tracking_interval=$ppctrack;
					else if($display_type ==1)
					{
						if($dsp ==2)
						$tracking_interval=$htmltrack;
						else
						$tracking_interval=$cpmtrack;
						
						$this->display_type_value=$dsp;
					}
					else if($display_type ==3)
					$tracking_interval=$sponsoredtrack;
					else if($display_type ==6)
					$tracking_interval=$cpatrack;

					
					if($tracking_interval ==0)
					$tracking_interval=1;
					
					$tracking_interval=$tracking_interval*1000;
					
					
					if($display_type !=13 && $adunittype !=14){?>
					
					<script data-cfasync="false" type="text/javascript">
					 window.setTimeout(function(){ 
					 var script=document.createElement("script");
					 	 script.setAttribute("data-cfasync","false");
					 	 script.type = "text/javascript";						 
					     script.async=1;
						 script.src="<?php echo $track_base_url.TRACK_DIR.'/index.php?page=click/data/{STRINGDATA}/{MDSTRINGDATA}/{MDSTRINGTIME}/{MDSTRINGCOUNTRY}';?>";
						 {DATAHEADAPPEND}
					  }, <?php echo $tracking_interval;?>);
				   </script>
				   <?php		
				   }
				}
			 }
	 	}
	}
	 
	
	 
	if($originalcnts ==0 && $sponsored_enabled ==1 && $display_type ==3 && $sid >0 && $adunittype !=14)
	{	
		if($result['banner_type'] !=1)
		{?>
		<div class="adv-here" style="width: <?php echo $result['width'];?>px;height: <?php echo $result['height'];?>px;">
		<a target="_blank" href="<?php echo $this->make_base_url("dispatch/sponsored/25/".$sid);?>">Advertise Here!</a>
		</div>
		<?php }else{?>
		<script data-cfasync="false" type="text/javascript">
		var skipinter='<?php echo $skipinterval;?>';
		var skipinterval=window.setInterval(function(){
			skipinter=skipinter-1;
			document.getElementById('skip-counter').innerHTML=skipinter;

			if(skipinter <=0)
			{
				window.clearInterval(skipinterval);
				window.parent.postMessage('<?php echo $intercontent1;?>',"*");
			}
		}, 1000);
		
		function CloseSkip()
		{
			window.clearInterval(skipinterval);
			window.parent.postMessage('<?php echo $intercontent1;?>',"*");
		}
		
		function CloseSkipAd()
		{
			skipinter=0;
		}
		</script>
		<span class="skip-div">
		<div class="skip-counter" id="skip-counter"><?php echo $skipinterval;?></div>
		
		<?php if($skipenabled ==1){?>
		<span class="skip-button" onclick="CloseSkip();"></span>
		<?php }?>
		</span>
		
		<div class="adv-here" style="width: <?php echo $result['width'];?>px;height: <?php echo $result['height'];?>px;"><a target="_blank" href="<?php echo $this->make_base_url("dispatch/sponsored/25/".$sid);?>" onclick="CloseSkipAd();">Advertise Here!</a></div>
		<?php }?>
	<?php } else if($puboriginalcnts >0) {
		
	$pubad=$this->get_result('pubad');	
		
	$default_id_string='';
	foreach($pubad as $key=>$pub_result)
	{
			if($native ==1)	{?>

			<div class="inner_dv1 inner_cap" id="innerdv_<?php echo $pub_result['aid'];?>_<?php echo $aduid;?>" 
			style="<?php if($responsive ==0 && $i == $columns){?> clear:both; <?php } if($responsive ==1){?>min-height: <?php echo $singleheight1; ?>px; min-width: <?php echo $singlewidth1;?>px;<?php } else {?>height:<?php echo $singleheight1;?>px;width:<?php echo $singlewidth1;?>px;padding: <?php echo $padding;?>px;<?php }if($result['lineseperator']==1 && $i < $totalnumber-1 ){ ?>border-right:solid 1px <?php echo $result['br_color']; ?>; <?php } ?>direction:<?php echo $dir;?>">
			
			<table style="width: 100%;">
			
			<?php if($adunittype ==4 && $image_position ==1){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			<tr>
			<?php if($adunittype ==4 && $image_position ==0){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			<td <?php if($adunittype ==4 && $image_position ==1 ){?> style="text-align: center;" <?php }?>>
			
		
			<div class="title"><a href="<?php  echo $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank" style="padding-left:0px;"><?php echo $pub_result['title']; ?></a></div>
			<?php if(($adunittype==4 && $txtimg_desc_display==1) ||($adunittype==1 && $txt_desc_display==1)) {?>
			
			<div class="description" style="padding-left:0px;"><?php echo $pub_result['description']; ?></div>
			<?php } if(($adunittype==4 && $txtimg_url_display==1) ||($adunittype==1 && $txt_url_display==1)){?>
			<div class="url"><a href="<?php  echo $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank" style="padding-left:0px;"><?php echo $pub_result['display_url'];?></a></div>
			<?php }?>
			</td>

			</table>
			</div>	
			<?php 
			
			} 
			else if($adunittype ==13)
			{
				$controls_enabled=Configuration::get_instance()->read('html5_video_controls_enabled');
				$autoplay_enabled=Configuration::get_instance()->read('html5_video_autoplay_enabled');
				$video_muted=Configuration::get_instance()->read('html5_video_mute_enabled');
				?>
			 	
			<video width="<?php echo $player_width;?>" height="<?php echo $player_height;?>" <?php if($video_muted ==1){?> muted <?php }?> <?php if($controls_enabled ==1){?>controls<?php }?> <?php if($autoplay_enabled ==1){?>autoplay<?php }?>>
			<source src="<?php echo $base_url.DATA_DIR.'/video/'.$pub_result['aid'].'/'.$pub_result['banner'];?>" type="<?php echo $pub_result['mime_type'];?>"></source>
			<?php echo 'Your browser does not support HTML5 video';?>
			</video>
			
			
			<?php } else if($adunittype ==14){	
        
	        $skin_banners=array();
	        $skin_height=array();
	        $banner_list=json_decode(html_entity_decode($pub_result['banner']),1);
	           
	        foreach($banner_list as $key => $value) 
	        {
	        	$image_array=explode('_',$value);
	        	
	            $skin_key=$image_array[0];  //Width of skin banner
	
	        	$skin_banners[$skin_key]=$base_url.DATA_DIR.'/'.$pub_result['aid'].'/'.$value;
	        	$skin_height[$skin_key]=$image_array[1];
	        }
	        
	        $skin_content['click_url']=$track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid'];
	        $skin_content['skin_banners']=$skin_banners;
			$skin_content['skin_height']=$skin_height;
	        
	        $skin_content=json_encode($skin_content);
        	?>
		        <script data-cfasync="false" type="text/javascript">
		        window.parent.postMessage('<?php echo $skin_content;?>',"*");
        
		        </script>
		        
			<?php } else if($adunittype ==2){
			if($pub_result['dsp'] ==0 || $pub_result['dsp'] ==1 || $pub_result['dsp'] ==6) {?>	
			<a target="_blank" href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>">
			<img style="height:<?php echo $result['height']; ?>;width:<?php echo $result['width']; ?>;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" />
			</a>
			<?php }
			else if($pub_result['dsp'] ==2)
			echo $pub_result['description'];
		}
		else if($adunittype ==5){?>
		<script data-cfasync="false" type="text/javascript">
		var skipinter='<?php echo $skipinterval;?>';
		var skipinterval=window.setInterval(function(){
			
			skipinter=skipinter-1;
			document.getElementById('skip-counter').innerHTML=skipinter;
		
			if(skipinter <=0)
			{
				window.clearInterval(skipinterval);
				window.parent.postMessage('<?php echo $intercontent1;?>',"*");
			}
		}, 1000);

		function CloseSkip()
		{
			window.clearInterval(skipinterval);
			window.parent.postMessage('<?php echo $intercontent1;?>',"*");
		}
		
		function CloseSkipAd()
		{
			skipinter=0;
		}
		</script>
		<span class="skip-div">
		<div class="skip-counter" id="skip-counter"><?php echo $skipinterval;?></div>
		
		<?php if($skipenabled ==1){?>
		<span class="skip-button" onclick="CloseSkip();"></span>
		<?php }?>
		</span>
		
		<a target="_blank" href="<?php  echo $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" onclick="CloseSkipAd();">
		<img style="height:<?php echo $result['height']; ?>;width:<?php echo $result['width']; ?>;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" />
		</a>		
	 	<?php } else {?>
	 	
			<div class="inner_dv1" style="height: <?php if($result['orientaion']==2){echo $result['height'];}else {echo $singleheight;} ?>px;   width: <?php if($result['orientaion'] == 2){echo $singlewidth;}else {echo $result['width'];} ?>px;<?php     if($result['lineseperator']==1 && $i < $totalnumber-1){ if($result['orientaion'] == 2){?>border-right:solid 1px <?php echo $result['br_color'];?>;<?php }else{?>border-bottom:solid 1px <?php echo $result['br_color'];?>;<?php }}?>overflow: hidden;">
			 
			<table style="width: 100%;">
			
			<?php if($adunittype ==4 && $result['image_position'] ==1){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			<tr>
			<?php if($adunittype ==4 && $result['image_position'] ==0){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			<td <?php if($adunittype ==4 && ($result['image_position'] ==1 || $result['image_position'] ==3)){?> style="text-align: center;" <?php }?>>
			
			<div class="title"><a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><?php echo $pub_result['title']; ?></a></div>
			<div class="description"><?php echo $pub_result['description']; ?></div>
			<div class="url"><a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><?php echo $pub_result['display_url'];?></a></div>
			
			</td>
			
			<?php if($adunittype ==4 && $result['image_position'] ==2){?>
			<td style="width: <?php echo $bannerwidth; ?>px;">
			<a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td>
			<?php }?>
			</tr>
			
			<?php if($adunittype ==4 && $result['image_position'] ==3){?>
			<tr><td colspan="3" style="text-align: center;">
			<a href="<?php  echo  $track_base_url.TRACK_DIR.'/index.php?page=click/default/'.$pub_result['aid'].'/'.$result['auid']; ?>" target="_blank"><img class="image-box" style="height: <?php echo $bannerheight; ?>px;width: <?php echo $bannerwidth; ?>px;margin: 0px auto;" src="<?php echo $base_url.DATA_DIR;?>/<?php echo $pub_result['aid'].'_'.$pub_result['banner'] ; ?>" border="0" /></a>
			</td></tr>
			<?php }?>
			
			</table>
			</div>		
		
		<?php
	 	}
	 
		if($default_id_string !='')
		$default_id_string.='_';
		 
		$default_id_string.=$pub_result['aid'];
		 
		$i++;
	 }
	 ?>
	 <script data-cfasync="false" type="text/javascript" src="<?php echo $this->make_base_url('click/default_update/'.$default_id_string,TRACK_DIR);?>"></script>
	 <?php }?>
	 
	 
<?php if($adunittype !=14){?>	 
</div>	
</body>	
</html>
<?php }?>

<?php }?>
<script data-cfasync="false" language="javascript" type="text/javascript">
<?php if($display_type !=13 && $adunittype !=14){?>
function SlideLeft()
{
	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');

	currentindex=$('#currentindex').val();

	if(currentindex ==1)
	return;

	$('.sliderightinner-style').show();

	if(parseInt(currentindex)-1 == 1)
	$('.slideleftinner-style').hide();


	$('#currentindex').val(parseInt(currentindex)-1);


	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');

	$(".slideinner-style").animate({"left": (parseInt(marginleft)+parseInt(holderwidth))+"px"}, "slow");
}


function SlideRight()
{
	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');


	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');


	currentindex=$('#currentindex').val();
	blockcount=$('#blockcount').val();


	if(currentindex == blockcount)
	return;

	$('.slideleftinner-style').show();

	if(parseInt(currentindex)+1 == blockcount)
	$('.sliderightinner-style').hide();


	$('#currentindex').val(parseInt(currentindex)+1);

	$(".slideinner-style").animate({"left": (parseInt(marginleft)-parseInt(holderwidth))+"px"}, "slow");
}

<?php }?>


<?php if($adunittype !=14){?>
function AdmarketCreditLoad()
{
	document.getElementById("cimdv").style.display="none";
	document.getElementById("cdv").style.display="";
}
function AdmarketCreditDisable()
{
	<?php if($display_mouse_hover ==1){?>
	document.getElementById("cimdv").style.display="";
	document.getElementById("cdv").style.display="none";
	<?php }else{?>
	document.getElementById("cimdv").style.display="none";
	document.getElementById("cdv").style.display="";
	<?php }?>
}


<?php if($display_mouse_hover ==0 && $credit_text != ""){?>
AdmarketCreditLoad();
<?php }?>


<?php 
if($native ==0)
{
	if($bannerflag ==1 && $expandableflag ==1 && $passcontent !="")
	{?>
		function LoadExpandableData(data)
		{
			if(data ==0)
			data='<?php echo $passcontent1;?>';
		
			
			window.parent.postMessage(data,"*");
		}

		LoadExpandableData('<?php echo $passcontent;?>');
<?php }}?>
<?php }?>

<?php if($display_type !=13){?>

id=<?php echo $aduid;?>;

<?php if($native ==1){?>

var minwidth =<?php echo $singlewidth1;?>;
var padding  =<?php echo $padding;?>;
	col		 =<?php echo $columns;?>;
	row		 =<?php echo $rows;?>;
	total	 =<?php echo $number;?>;
	
	
	$(document).ready(function()
	{
		var height	=0;
		var width	=0;
		
		<?php if($responsive==1){?>
		height	= align_div();
	    <?php } else {?>
		height=<?php echo $blockheight+2;?>;
		width=<?php echo $blockwidth+2;?>;
		<?php } ?>
	
		pass_message('{"operation":"nativedata","auid":<?php echo $aduid;?>,"height":'+height+',"width":'+width+',"responsive":<?php echo $responsive;?>}');
	}); 
	
	<?php if($responsive ==1){?>
	var eventMethod =window.addEventListener ? "addEventListener" : "attachEvent";
	var eventer = window[eventMethod];
	var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
	eventer(messageEvent,  function(e) 
	{
	   var data = e.data; 

	   if(data == id)
	   {   
		   height	= align_div();

		   pass_message('{"operation":"nativedata","auid":<?php echo $aduid;?>,"height":'+height+',"width":0,"responsive":<?php echo $responsive;?>}');
	   }
	}, false);
	<?php }?>
	
	function align_div()
	{
		var fwidth		= $("#market_main_"+id).css('width');

		var minwidth1	= Number(minwidth)+Number(2*padding);

		awidth			= fwidth.slice(0,-2);
		p				= padding/awidth*100;
		no				= Math.floor(awidth/minwidth1);


		if(col < no)
		no=col;
		
		if(total < no)
		no=total;
	
		swidth=100/no;
		
		swidth=Math.floor(swidth-(2*p));


		$(".inner_dv1").css('padding',p+'%');
		$(".inner_dv1").css('width',swidth+'%');
		$(".inner_dv1").css('clear','none');

		i=1;
		
		$('.inner_dv1').each(function(){

			dvid = this.id;
			
			if(i%no ==1)
			$('#'+dvid).css('clear','both');
	
			i++;
		});
		
		fheight	= $("#market_main_"+id).height();
		fheight	= Number(fheight+2);
		
		return fheight;
	}
	
	function pass_message(data)
	{ 
		window.parent.postMessage(data,"*");
	}
	
<?php }?>


<?php if($adunittype ==14 && $skin_enabled ==1){?>
    var skin_render=1;

    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";

	eventer(messageEvent, function (e) 
    {
            var response=e.data;
            try 
            {
                responsedata=JSON.parse(response);

                if(responsedata !="")
                {
                    if(responsedata.adcode == <?php echo $adcodeid;?> && responsedata.message =='skin_success')
                    skin_render=1; 
                    else
                    skin_render=0;
                }
                else
                skin_render=0; 

	            if(skin_render ==1)
	            {
					 window.setTimeout(function()
					 { 
						 var script=document.createElement("script");
						 	 script.setAttribute("data-cfasync","false");
						 	 script.type = "text/javascript";							 
						     script.async=1;
						     script.src="<?php echo $track_base_url.TRACK_DIR.'/index.php?page=click/data/{STRINGDATA}/{MDSTRINGDATA}/{MDSTRINGTIME}/{MDSTRINGCOUNTRY}';?>";							 
							 
						     {DATAHEADAPPEND}
					 }, <?php echo $tracking_interval;?>);
	            }
            } catch (e) {}
     }, false);
<?php }?>



<?php }else{?>

var video_timer=0;
var video_tracked=0;
var interval;
function TrackData(operation,duration,trackingtime)
{
	//operation => 1 Play
	//operation => 2 Pause
	
	
	if(video_tracked ==1)
	return;

	if(operation ==1)
	{
		interval=window.setInterval(function(){ 

			video_timer=video_timer+1;

			if(video_timer >= trackingtime || video_timer >= duration)
			{
				window.clearInterval(interval);	

				video_timer=0;
				video_tracked=1;

				var script=document.createElement("script");
				script.setAttribute("data-cfasync","false");
			 	script.type = "text/javascript";					
			    script.async=1;
				script.src="<?php echo $track_base_url.TRACK_DIR.'/index.php?page=click/data/{STRINGDATA}/{MDSTRINGDATA}/{MDSTRINGTIME}/{MDSTRINGCOUNTRY}';?>";
				{DATAHEADAPPEND}
			}
		
		}, 1000);

	}
	else if(operation ==2)
	{
		video_timer=video_timer+1;
		window.clearInterval(interval);	
	}
}
<?php }?>
</script>
<?php }?>