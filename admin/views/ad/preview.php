<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />

<?php

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');

$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];


$banner=$val['banner'];

$afsum=$this->get_variable('afsum');


$amtusd=$val['total_budget_used']-$afsum;
if($amtusd < 0)
$amtusd=0;


$expandable_banner="";
$expandable=0;
$expandable_enabled=$this->get_addon_status('expandable-banners_enabled');

if($type ==2 && $expandable_enabled ==1)
{
	$expandable_banner=$val['expandable_banner'];
	$expandable=$val['expandable'];
	
	if($expandable_banner =="")
	$expandable=0;	
}


if($type==2 || $type ==5 || $type ==7)
{
	$res1=$this->get_result('res1');
	$val1=$res1[0];
	
	$diamensions=$val1['width']." x ".$val1['height'];

}


$aid=$this->get_variable('aid');
$frompg=$this->get_variable('frompg');


$amount_spend=$this->get_variable('amount_spend');
$amount_spend_today=$this->get_variable('amount_spend_today');
$daily_budget=$this->get_variable('daily_budget');
$total_budget=$this->get_variable('total_budget');


$adp123=$val['display_type'];


if($adp123 ==3)
{
			$spimpression=intval($this->get_variable('spimpression'));
			$spclick=intval($this->get_variable('spclick'));

}


$pop_enabled=$this->get_addon_status('pop-ads_enabled');

?>
<style type="text/css">
.ad-popup-div {margin-left: 0px;}

.modal-dialog
{
    margin: 80px auto;
    width: 90%;
}

.modal-content 
{
    border-radius:0px;
    min-height:150px;
}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}

b, strong
{
	font-weight:normal;
}

table
{
	border-collapse: unset;
}

body
{
	font-size: 13px;
}

.footer i
{
   margin: 0px 5px;
}

.sub_menu_main a:link
{
 	text-decoration: underline;
 	color:#333333; 	 		
}

.sub_menu_main a:hover
{
 	text-decoration: none;
 	color:#333333; 	 		
}
</style>

<?php if($type ==7){?>
<style type="text/css">
.dummytitle{color: <?php echo $val['title_color'];?> !important;}
.dummydescription{color: <?php echo $val['desc_color'];?> !important;}
.dummyurl{color: <?php echo $val['url_color'];?> !important;}
.dummyprice{color: <?php echo $val['price_color'];?> !important;}
.dummyofferprice{color: <?php echo $val['offer_price_color'];?> !important;}

.dummybutton {color: <?php echo $val['ab_text_color'];?> !important;background-color: <?php echo $val['ab_background_color'];?> !important;}
.dummybutton:hover {background-color: <?php echo $val['ab_bhover_color'];?> !important;}

.span-text{color:<?php echo $val['ah_text_color'];?> !important;}

.span-button-preview{color:<?php echo $val['abh_text_color'];?> !important;background-color: <?php echo $val['abh_background_color'];?> !important;}
.span-button-preview:hover {background-color: <?php echo $val['abh_bhover_color'];?> !important;}

.display-outer-style{border-color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['background_color'];?> !important;}
.singleadsection-style{border-color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['ad_background_color'];?> !important;}

.slideleftinner-style, .sliderightinner-style {border-color:<?php echo $val['border_color'];?> !important;color:<?php echo $val['border_color'];?> !important;background-color: <?php echo $val['background_color'];?> !important;}
</style>
<?php }?>



<table style="width: 100%">
<?php if($type==2 || $type ==5 || $type ==7 || $type ==13){ ?>
<tr>
<td height="20px" colspan="3"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?>

<?php if($val['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

&nbsp;&nbsp;

<?php if($type ==2 && $expandable ==1){ echo $this->get_label('expandable');} ?>

&nbsp;&nbsp;

<?php 
if($adp123 ==0 || $adp123 ==1 || $adp123 ==13 || $adp123 ==6)
{
	if($val['uid'] >0) { echo $this->get_label('amount used');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);}
}
else if($adp123 ==3){?>
<?php echo $this->get_label('impressions');?> : <?php echo $spimpression;?>&nbsp;&nbsp;
<?php echo $this->get_label('clicks');?> : <?php echo $spclick;?>
<?php }?>

</td>
<td colspan="3" align="right"><?php echo $this->get_label('created by');?> : <?php if($val['uid'] >0) { ?><a href="<?php echo $this->make_url("user/profile/".$val['uid']."/0");?>"><?php echo $this->get_variable("usname");?></a><?php } else { echo $this->get_label('admin'); }?></td>
</tr>

<tr ><td colspan="6" style="border: 1px solid #CCCCCC;">
<?php if($type==2 || $type ==5){ ?>
<div class="ad-popup" style="max-width: 150px;padding: 5px;">
<a href="<?php echo $val['click_url'];?>"><img style="max-width: 500px;max-height: 90px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" /></a>


<div class="ad-popup-div">
<?php if($expandable ==0){?>
<img alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" />
<?php }else{?>
<img alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
<?php }?>
</div>
</div>

<?php } else if($type==13){ ?>

<video width="300" height="225" controls style="margin: 5px;">
<source src="<?php echo '../'.DATA_DIR.'/video/'.$aid.'/'.$val['banner'];?>" type="<?php echo $val['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

<?php }else{?>

<div style="width: 95%;padding: 5px;min-height: 60px;margin-top: 10px;">

	<div class="layout-div-create" style="width: 100%;">
	<?php echo $this->get_display_layout_name($val['display_layout']);?>&nbsp;&nbsp;
	
	<span class="link_button show-span" onclick="LoadLayoutPreview(<?php echo $val['display_layout'];?>);"><?php echo $this->get_label('show preview');?></span>
	
	<span class="link_button hide-span" onclick="LoadLayoutPreview(0);"><?php echo $this->get_label('hide preview');?></span>
	
	
	</div>
		
	<div class="layout-preview" id="layout-preview-<?php echo $val['display_layout'];?>" style="margin-top: 20px;display: none;">	
	<?php echo $this->get_display_preview($val['display_layout'],$aid,1);?>
	</div>

</div>

<?php }?>
</td></tr>

<tr>
<td colspan="4" style="width: 650px;height: 20px;"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?>

&nbsp;&nbsp;&nbsp;
<?php if($type==2 || $type ==5 || $type ==7){?>
<?php echo $this->get_label('banner dimension');?> : <?php echo $diamensions;?>
&nbsp;&nbsp;&nbsp;
<?php }?>


<?php 
if($type ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($val['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo $this->get_label('aspect ratio').' : '.$aspect_ratio;
}
?>

&nbsp;&nbsp;&nbsp;


<?php 
if($val['uid'] >0 && $adp123 != 3) 
{
	echo $this->get_label('default rate')." : ".$this->get_money_format($val['default_rate']).'&nbsp;&nbsp;&nbsp;'; 
	
	echo $this->get_label('daily budget')." : ".$this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget).'&nbsp;&nbsp;&nbsp;';
	
	echo $this->get_label('total budget')." : ".$this->get_money_format($amount_spend).' / '.$this->get_money_format($total_budget).'&nbsp;&nbsp;&nbsp;';	
}
?>
</td>


<td>


</td>


<td style="float: right;margin-top: 10px;">
<?php 
if($frompg !=4){    
?>

<?php $pstatus=$val['pause_status'];if($pstatus==1){?>
   <a href="<?php echo $this->make_url("ad/change_pause_status/".$aid."/0/4");?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume');?>"></i></a>
   <?php }else{?>
       <a href="<?php echo $this->make_url("ad/change_pause_status/".$aid."/1/4");?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause');?>"></i></a>
       
   <?php }?>


<a href="<?php echo $this->make_url("ad/change_status/".$aid."/".$frompg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a>

<?php }?>



</td>

</tr>


<?php } else if($type==1 || $type ==0 || $type ==11 || $type ==14 || $type==9 || $type ==18) { ?>
<tr>
<td height="20px" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?>

<?php if($val['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

&nbsp;&nbsp;


<?php 
if($adp123 ==0 || $adp123 ==1 || $adp123 ==9)
{
	if($val['uid'] >0) { echo $this->get_label('amount used');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);}
	
	
	if($adp123 == 9 || $type == 9)
	{
		$poptype="";
		
		if($val['pop_type'] ==1)
		$poptype=$this->get_label('popup');
		else if($val['pop_type'] ==2)
		$poptype=$this->get_label('popunder');
		else if($val['pop_type'] ==3)
		$poptype=$this->get_label('poptab');
		
		if($poptype !="")
		echo '&nbsp;&nbsp;&nbsp;'.$poptype;
	}
	
	
}
else if($adp123 ==3){?>
<?php echo $this->get_label('impressions');?> : <?php echo $spimpression;?>&nbsp;&nbsp;
<?php echo $this->get_label('clicks');?> : <?php echo $spclick;?>
<?php }?>
</td>
<td colspan="3"  align="right"><?php echo $this->get_label('created by');?> : <?php if($val['uid'] >0) { ?><a href="<?php echo $this->make_url("user/profile/".$val['uid']."/0");?>"><?php echo $this->get_variable("usname");;?></a><?php } else { echo $this->get_label('admin'); }?></td>

</tr>



<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;outline: 1px solid #CCCCCC;">


<?php if($type == 1 || $type == 11 || $type == 18){?>
<span style="vertical-align: top;">

<?php if($type == 11 || $type == 18){

$textimagelink="";

if(file_exists('../'.DATA_DIR.'/'.$val['id'].'_'.$val['banner']))
$textimagelink='../'.DATA_DIR.'/'.$val['id'].'_'.$val['banner'];
else
$textimagelink='';
	
?>
<div style="float:left;vertical-align: middle;padding-right: 2px;border-bottom: 0px;margin-right:3px;margin-top:4px;">
<img src="<?php echo $textimagelink;?>">
</div>
<div style="padding-top:0px;">
<span class="title" style='vertical-align: top;'><a href="<?php echo $val['click_url'];?>"><?php echo $val['title'];?></a></span>
<br>
<span class="description"><?php echo $val['description'];?></span>
<br>
<span class="url"><?php echo $val['display_url'];?></span>
<br>
</div>


<?php } else{ ?>

<a href="<?php echo $val['click_url'];?>"><?php echo $val['title'];?></a>
<br>
<span><?php echo $val['description'];?></span>
<br>
<span><?php echo $val['display_url'];?></span>
<?php }?>
</span>
<?php }else if(($type == 0 && $adp123 ==9) || $type == 9){?>
<div style="height: 50px;padding-top: 15px;">
<a style="cursor: pointer;" <?php if($pop_enabled ==1){?>onclick="LoadPop('<?php echo $val['click_url'];?>',<?php echo $val['pop_type'];?>);"<?php }?>><?php echo $val['click_url'];?></a>
</div>

<?php } else if(($type == 0 && $adp123 ==12) || $type ==14){

	if($type ==14)
	{
		$json_array=$this->get_array('json_array');
		
		$imagerow=$json_array[0];
	}
	else
	$imagerow=$this->get_result('imagerow');	
	
		
	?>
	
	<div style="height: 50px;padding-top: 15px;">
	<span class="get_popup_btn link_button" data-toggle="modal" data-target="#Affiliate-Preview"><?php echo $this->get_label('preview');?></span>
	
	</div>


<div class="modal fade" id="Affiliate-Preview" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title text-center">
        		<?php 
        		if($type ==14)
        		echo $this->get_label('skin ads banners');
        		else
        		echo $this->get_label('manage affiliate banners');
        		?>
        		</h4>
      		</div>
      		
      		
      		<div class="modal-body row" style="padding: 10px 15px 25px;">
      		
      		<div style="margin: 5px;">
      		<div id="delete-message" style="font-size: 14px;display: none;text-transform: none;"></div>
      		</div>
      		
							  	
<div style="overflow: auto;max-height: 450px;margin: 5px;">

<?php if(count($imagerow) ==0){?>

<div>
<?php echo $this->get_label('no banners found');?>
</div>
<?php }else{?>

<?php foreach($imagerow as $key1=>$value1){

	
	$bannerpath='';
	
	if($type ==14)
	{
		if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$value1))
		$bannerpath='../'.DATA_DIR.'/'.$aid.'/'.$value1;
	}	
	else
	{
		$currentimage=$this->get_banner_name($aid,$value1['banner_size']);
		if($currentimage !="")
		{
			if(file_exists('../'.DATA_DIR.'/banners/'.$aid.'/'.$currentimage))
			$bannerpath='../'.DATA_DIR.'/banners/'.$aid.'/'.$currentimage;
		}
	}	
	?>

<?php if($bannerpath !=""){?>
<div <?php if($adp123 ==12){?>id="div-dimension-<?php echo $value1['id'];?>"<?php }?> style="margin: 5px;">
<div style="white-space: nowrap;cursor: pointer;margin: 10px 0px;">
<bdi>
<?php 
if($type ==14)
{
	$array=explode('_',$value1);
	
	if(isset($array[0]) && isset($array[1]))
	echo $array[0].' x '.$array[1];
}
else
echo $value1['width'].' x '.$value1['height'];
?>

<?php if($adp123 ==12){?>
<i class="fa fa-times" onclick="DeleteImage('<?php echo $value1['id'];?>');" title="<?php echo $this->get_label('delete');?>" alt="<?php echo $this->get_label('delete');?>"></i>

<span id="load<?php echo $value1['id'];?>" style="display: none;position: absolute;margin-left: 5px;"><img src="images/load.gif"/></span>
<?php }?>
</bdi>
</div>
<div>
<img class="img-responsive" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
</div>

</div>
<?php }}}?>
</div>


	  	
	      	</div>
    	</div>
	 </div>
</div>	
<?php }?>
</td>
</tr>

<tr>
<td style="width: 450px;height: 20px;"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?></td>
<td></td>


<td>
<?php 
if($val['uid'] >0 && $adp123 != 3) 
{
	echo $this->get_label('default rate')." : ".$this->get_money_format($val['default_rate']).'&nbsp;&nbsp;&nbsp;'; 
	
	echo $this->get_label('total budget')." : ".$this->get_money_format($amount_spend).' / '.$this->get_money_format($total_budget).'&nbsp;&nbsp;&nbsp;';	
}
?>
</td>
<td></td>

<td style="float: right;margin-top: 10px;">

<?php 
if($frompg !=4){
?>
<?php $pstatus=$val['pause_status'];if($pstatus==1){?>
   <a href="<?php echo $this->make_url("ad/change_pause_status/".$aid."/0/4");?>"><i class="fa fa-play-circle-o resume-icon" title="<?php echo $this->get_label('resume');?>"></i></a>
   <?php }else{?>
       <a href="<?php echo $this->make_url("ad/change_pause_status/".$aid."/1/4");?>"><i class="fa fa-pause pause-icon" title="<?php echo $this->get_label('pause');?>"></i></a>
       
   <?php }?>

<a class="link_button" href="<?php echo $this->make_url("ad/change_status/".$aid."/".$frompg);?>"><?php echo $this->get_label('operations');?></a>

<?php }?>
</td>

</tr>

<?php 
}

?>

<tr>
<td ></td><td ></td>
<td>
<?php if($val['cpm_complete']==0 && $val['display_type']==6){?>

<label  style="padding-left: 0px;">

<?php echo $this->get_label('cpm budget readonly');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)


<?php
if($_POST)
    $def_val= $this->read_post_param('default_rate');
    else 
    $def_val= $val['default_rate'];
if($_POST['stat'])
    $def_val= $val['default_rate'];;
   

        //if($_POST) echo $this->read_post_param('default_rate'); else echo $val['default_rate'];if($_POST['stat']) echo $val['default_rate'];

?>




</label>&nbsp;&nbsp;
<input type="text" name="default_rate" id="default_rate" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('default_rate');" value="<?php echo $def_val;//if($_POST) echo $this->read_post_param('default_rate'); else echo $val['default_rate']; if($_POST['stat']) echo $val['default_rate'];?>" />&nbsp;&nbsp;

<input type="hidden" name="def" id="def" value="<?php echo $def_val; //if($_POST['stat']) echo $this->read_post_param('default_rate'); else echo $val['default_rate'];if($_POST['stat']) echo $val['default_rate'];?>">
<input type="button" name="add" id="add_btn" value="<?php echo $this->get_label('add');?>" onclick="update_budget();" >
&nbsp;&nbsp;&nbsp;
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span id="budget_msg"></span></div>
<?php }?>
</td></tr>
</table>
<script type="text/javascript">
function CheckDecimalPlaces(elementId)
{
	decimal_places = <?php echo intval(Configuration::get_instance()->read('decimal_place'));?>;

	dataValue      = $('#'+elementId).val();

	dataValueArray = dataValue.split('.');

	if(dataValueArray.length > 1 && dataValueArray[1].length > decimal_places)
	{
		dataValueArray[1] = dataValueArray[1].substring(0,decimal_places);

		$('#'+elementId).val(dataValueArray.join('.'))
	}
}
function update_budget()
{
	
    var default_rate=document.getElementById('default_rate').value;
	var parameter='aid='+<?php echo $aid;?>+'&default_rate='+default_rate;		
     //alert(parameter);
	$.ajax({
        type: "POST",
        url:"<?php echo $this->make_url("ad/update_pricing");?>",
		data:parameter,
		success: function(data)
		{
			
			if(data==1)
			{
				$('#budget_msg').css("color","green");
				$('#budget_msg').html('updated successfully');
			}
			else if(data==2)
			{
				$('#budget_msg').css("color","red");
				$('#budget_msg').html('value should be greater than zero');
			}
			else
			{
				$('#budget_msg').css("color","red");
				$('#budget_msg').html('cannot update rate');
			}

		}
		
	
});
}

</script>
<?php if($pop_enabled ==1 && ($adp123 == 9 || $type == 9)){ ?>

<script type="text/javascript">
function LoadPop(url,type)
{
	if(type ==1)
	window.open(url,'','width='+'<?php echo Configuration::get_instance()->read('pop_window_width');?>'+'px,height='+<?php  echo Configuration::get_instance()->read('pop_window_height');?>+'px'); //Up
	else if(type ==2)
	PopWindowUnder(url,<?php echo Configuration::get_instance()->read('pop_window_width');?>,<?php  echo Configuration::get_instance()->read('pop_window_height');?>);	//Under
	else if(type ==3)
	window.open(url,'_blank'); //New Tab
}
</script>
<?php }?>


<?php if($adp123 ==12){?>
<script type="text/javascript">
function DeleteImage(id)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this affiliate banner');?>"))
	{
		$("#load"+id).show();

		aid=<?php echo $aid;?>;

		dataparam="id="+id+"&aid="+aid;

		var urlvalue='<?php echo $this->make_url("ad/delete_banner");?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
				message="";


				if(msg ==1)
				message="<?php echo $this->get_message('invalid operation');?>";
				else if(msg ==2)
				{
					message="<?php echo $this->get_message('affiliate banner delete success');?>";

					$('#div-dimension-'+id).remove();
				}


				$('#delete-message').html(message);
	
				if(msg ==1)
				$('#delete-message').css('color','red');
				else
				$('#delete-message').css('color','green');	

				$('#delete-message').show();
				

				$("#load"+id).hide();	

				setTimeout(function(){
					$('#delete-message').slideUp();
				},1500);
			}
		});
	}
}
</script>
<?php }?>
<script type="text/javascript">
function LoadLayoutPreview(id)
{
	if(id >0)
	{
		if($('#layout-preview-'+id).css('display') =='none')
		{
			$('.layout-preview').show();
			$('#layout-preview-'+id).css('display','block');
		}
		else
		{
			$('.layout-preview').hide();
			$('#layout-preview-'+id).css('display','none');
		}

		$('.show-span').hide();
		$('.hide-span').show();		
	}
	else
	{
		$('.layout-preview').hide();
		$('.hide-span').hide();
		$('.show-span').show();
	}
}







function SlideLeft(direction)
{
	//direction => 0 ltr
	//direction => 1 rtl

	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');


	
	currentindex=$('#currentindex').val();
	blockcount=$('#blockcount').val();

	if(currentindex ==1)
	return;


	
	if(direction ==0)
	$('.sliderightinner-style').show();
	else if(direction ==1)
	$('.slideleftinner-style').show();	

	

	if(parseInt(currentindex)-1 == 1 && direction ==0)
	$('.slideleftinner-style').hide();
	else if(parseInt(currentindex)-1 == 1 && direction ==1)
	$('.sliderightinner-style').hide();

	
	
	$('#currentindex').val(parseInt(currentindex)-1);

	
	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');


	$(".slideinner-style").animate({"left": (parseInt(marginleft)+parseInt(holderwidth))+"px"}, "slow");
}


function SlideRight(direction)
{
	
	//direction => 0 ltr
	//direction => 1 rtl
	
	marginleft=$(".slideinner-style").css("left");
	marginleft=marginleft.replace('px');


	holderwidth=$('.slideinnerholder-style').css('width');
	holderwidth=holderwidth.replace('px');


	currentindex=$('#currentindex').val();
	blockcount=$('#blockcount').val();



	if(currentindex == blockcount)
	return;

	

	if(direction ==0)
	$('.slideleftinner-style').show();
	else if(direction ==1)
	$('.sliderightinner-style').show();	
	
	



	
	if(parseInt(currentindex)+1 == blockcount && direction ==0)
	$('.sliderightinner-style').hide();
	else if(parseInt(currentindex)+1 == blockcount && direction ==1)
	$('.slideleftinner-style').hide();

	
	
	$('#currentindex').val(parseInt(currentindex)+1);
		

	$(".slideinner-style").animate({"left": (parseInt(marginleft)-parseInt(holderwidth))+"px"}, "slow");
	
}

</script>
