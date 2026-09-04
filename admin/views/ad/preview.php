<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap-3.0.3.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/preview.js'></script>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-3.0.3.min.css" />

<?php
$resFontExternal        = $this->get_result("resFontExternal");

$bannerListArray = array();

$premium_ad_enabled = Configuration::get_instance()->read('allow_premium_ads');

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');

$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];

$html5=intval($val['html5']);
$banner=$val['banner'];


$textimageName = "";

$amtusd=$val['total_budget_used'];

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


if($type==2 || $type ==7 || $type ==11)
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

$pop_enabled=$this->get_addon_status('pop-ads_enabled');
?>
<style type="text/css">
<?php foreach($resFontExternal as $fkey=>$fvalue){?>
@font-face {
  font-family: <?php echo $fvalue['slug_name'];?>;
  src: url(<?php echo BASE.DATA_DIR.'/fonts/'.$fvalue['file_name'];?>);
}
<?php } ?>


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

.previewCloseDiv
{
	padding-top: 0px;
}
.previewSection
{
	margin-top: 0px;
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
<?php if(($adp123 == 3 &&  $type ==7) || ($adp123 != 3 && ($type==2 || $type ==7 || $type ==13 || $type == 18))){ ?>
<tr>
<td style="vertical-align: bottom;" colspan="3"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?>

<?php if($val['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

<?php if($premium_ad_enabled == 1 && $val['premium_ad'] == 1){?>
     <i class="fa fa-star" style="font-size: 16px;color:green;" title="<?php echo $this->get_label('premium ad');?>"></i>&nbsp;
<?php } ?>

<?php if($val['html5'] ==1){?>
<i class="fa fa-html5" style="font-size: 16px;" title="<?php echo $this->get_label('html5');?>"></i>&nbsp;
<?php }?>

&nbsp;&nbsp;

<?php if($type ==2 && $expandable ==1){ echo $this->get_label('expandable');} ?>

&nbsp;&nbsp;

<?php
if($adp123 ==0 || $adp123 ==1)
{
	if($val['uid'] >0) { echo $this->get_label('amount used');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);}
}
?>

</td>
<td style="vertical-align: bottom;" colspan="3" align="right"><?php echo $this->get_label('created by');?> : <?php if($val['uid'] >0) { ?><a href="<?php echo $this->make_url("user/profile/".$val['uid']."/0");?>"><?php echo $this->get_variable("usname");?></a><?php } else { echo $this->get_label('admin'); }?></td>
</tr>

<tr ><td colspan="6" style="border: 1px solid #CCCCCC;">
<?php if($type==2){ ?>


<?php if($html5 == 0){?>
<div class="ad-popup" style="max-width: 150px;padding: 5px;">
<a href="<?php echo $val['click_url'];?>"><img style="max-width: 500px;max-height: 90px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" /></a>

<?php if($expandable == 1){?>
<div class="ad-popup-div">
<img alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
</div>
<?php }?>
</div>
<?php }else{?>

<div class="ad-popup">

<input id="button-html5-show" type="button" class="link_button html5-preview" onclick="LoadHTML5Preview('<?php echo BASE.DATA_DIR."/html5/".$aid."/html5/index.html";?>');" value="<?php echo $this->get_label('show preview');?>" />

<input id="button-html5-hide" type="button" class="link_button html5-preview" onclick="HideHTML5Preview();" value="<?php echo $this->get_label('hide preview');?>" style="display: none;" />


<div id="iframe-html5-div" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;display: none;margin-top: 10px;">
<img id="iframe-loader" src="images/load.gif" style="position: absolute;display:none;" />
<iframe id="iframe-html5" src="" style="width: <?php echo $val1['width'];?>px;height: <?php echo $val1['height'];?>px;z-index: 1000;position: absolute;"></iframe>
</div>

</div>
<?php }} else if($type==13){ ?>

<video width="300" height="225" controls style="margin: 5px;">
<source src="<?php echo '../'.DATA_DIR.'/video/'.$aid.'/'.$val['banner'];?>" type="<?php echo $val['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

<?php }
else if($adp123 == 18 && $type == 18)
{
	$ad_details=json_decode($this->getAdDetails($val['id']),1);
	$img_icon_url=BASE.DATA_DIR."/notification_icons/".$aid."_".$ad_details['banner'];
	?>
<div class="ad-layout-content-inner">
<div class="row">
  <div class="col-sm-1">
  <img src="<?php  echo $img_icon_url;?>" width="70px;" height="70px;">
 
  </div>
  <div class="col-sm-2 prev_layout" >
  <span class="ad-layout-title" style="font-weight: bold;"><?php echo $ad_details['title'];?></span><br>
  <span class="ad-layout-description"><?php echo $ad_details['description'];?></span><br>
  </div>
</div>
</div>
<?php } else { ?>

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
<?php if($type==2 || $type ==7){?>
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

	echo $this->get_label('total budget')." : ".$this->get_money_format($amount_spend).' / '.$this->get_money_format($total_budget).'&nbsp;&nbsp;&nbsp;';
}
?>
</td>
<td></td>
<td style="float: right;margin-top: 10px;">

<?php
if($premium_ad_enabled == 1)
{
    if($val['premium_ad'] == 0)
		{?>
     <a href="<?php echo $this->make_url("ad/premium/".$aid."/1/".$frompg);?>"
       onclick="return confirm('<?php echo $this->get_message('do you really want to set this ad as premium ad');?>')"
       ><i class="fa fa-star" style="font-size: 16px;color:#8a8e8a;" title="<?php echo $this->get_label('make premium');?>"></i></a>
	 <?php }

	 if($val['premium_ad'] == 1){?>
		<a href="<?php echo $this->make_url("ad/premium/".$aid."/0/".$frompg);?>"
			onclick="return confirm('<?php echo $this->get_message('do you really want to remove premium status of this ad');?>')"
			><i class="fa fa-trash" style="font-size: 16px;color:#e60d53;" title="<?php echo $this->get_label('remove premium');?>"></i></a>
	 <?php }
}
?>


<?php
if($frompg !=4){
?>
<a href="<?php echo $this->make_url("ad/change_status/".$aid."/".$frompg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a>

<?php }?>



</td>

</tr>


<?php } else if($adp123 == 3 || $type== 1 || $type == 9 || $type == 11 || $type == 12 || $type == 14 || $type == 21) {?>
<tr>
<td style="vertical-align: bottom;" colspan="2"><span class="buttonsnew <?php if($val['status']==-1) {?>button_color_a<?php }?><?php if($val['status']==1) {?>button_color_b<?php }?><?php if($val['status']==0) {?>button_color_c<?php }?>"><strong><?php echo $this->get_ad_status($val['status']);?></strong></span>&nbsp;<?php if($val['pause_status']==1){?><span class="buttonsnew button_color_d"><strong><?php echo $this->get_label('paused');?></strong></span><?php }?>

<?php if($val['retargeting'] ==1){?>
<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>&nbsp;
<?php }?>

&nbsp;&nbsp;

<?php
if($adp123 ==0 || $adp123 ==1)
{
	if($val['uid'] >0) { echo $this->get_label('amount used');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);}

}
else if($adp123 ==3 && ($type == 2 || $type == 11))
{
	$bannerListArray = json_decode($val['banner_list'],1);

if (!empty($bannerListArray) && is_array($bannerListArray) && count($bannerListArray) > 0) { ?>

<div style="width: 50px;float: left;">
<i data-toggle="modal" data-target="#Affiliate-Preview" class="fa fa-laptop" style="cursor: pointer;font-size: 16px;" title="<?php echo $this->get_label('ad preview');?>" alt="<?php echo $this->get_label('ad preview');?>"></i>
</div>

<?php }}?>
</td>
<td style="vertical-align: bottom;" colspan="3" align="right"><?php echo $this->get_label('created by');?> : <?php if($val['uid'] >0) { ?><a href="<?php echo $this->make_url("user/profile/".$val['uid']."/0");?>"><?php echo $this->get_variable("usname");;?></a><?php } else { echo $this->get_label('admin'); }?></td>
</tr>
<tr>
<td colspan="5" style="padding: 5px;outline: 1px solid #CCCCCC;">
<?php
if($type == 1 || $type == 11)
{
	if($type == 11)
	{
		if(file_exists('../'.DATA_DIR.'/'.$val['id'].'_'.$val['banner']))
		$textimageName = $val['id'].'_'.$val['banner'];
	}
?>

<span>
<input type="button" class="link_button previewSectionShow" onclick="AdPreview();" value="<?php echo $this->get_label('show preview');?>" />
<img id="loading" src="images/load.gif" style="display: none;" />
</span>
<div class="previewCloseDiv" onclick="HidePreviewBox();" style="display: none;"><span>x</span></div>
<div class="previewSection" style="display: none;right: 0px;"></div>

<?php }else if($type == 9 || $type == 21){?>
<div style="height: 50px;padding-top: 15px;">
<a style="cursor: pointer;" onclick="LoadPop('<?php echo $val['click_url'];?>');"><?php echo $val['click_url'];?></a>
</div>

<?php }

 if($type == 12 || $type ==14 || ($adp123 ==3 && ($type == 2 || $type == 11))){


	if($type ==14)
	{
		$json_array=$this->get_array('json_array');

		$imagerow=$json_array[0];
	}
	else if($adp123 ==3)
	{
		$bannerListArray = json_decode($val['banner_list'],1);

		$imagerow = $bannerListArray;
	}
	else
	$imagerow=$this->get_result('imagerow');

	?>

	<?php if($adp123 ==3 && $type == 2){?>

<div class="ad-popup" style="max-width: 150px;padding: 5px;">

	<a href="<?php echo $val['click_url'];?>"><img style="max-width: 500px;max-height: 90px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" /></a>


<?php if($expandable == 1){?>
<div class="ad-popup-div">
<img alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
</div>
<?php }?>
</div>


	<?php }else if($adp123 !=3){?>
	<div style="height: 50px;padding-top: 15px;">
	<span class="get_popup_btn link_button" data-toggle="modal" data-target="#Affiliate-Preview"><?php echo $this->get_label('preview');?></span>
	</div>
    <?php } ?>




<div class="modal fade" id="Affiliate-Preview" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
    	<div class="modal-content login-modal">
      		<div class="modal-header login-modal-header">
        		<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
        		<h4 class="modal-title text-center">
        		<?php
        		if($type ==14)
        		echo $this->get_label('skin ads banners');
        	    else if($adp123 ==3)
        	    echo $this->get_label('ad banners');
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

<?php if(!is_array($imagerow) || count($imagerow) == 0){?>
<div>
<?php echo $this->get_label('no banners found');?>
</div>
<?php }else{?>

<?php foreach($imagerow as $key1=>$value1){

	$bannerpath  = '';
	$bannerID    = 0;
	$fromData    = 0;

	if($type ==14)
	{
		if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$value1))
		$bannerpath='../'.DATA_DIR.'/'.$aid.'/'.$value1;
	}
	else if($adp123 ==3)
	{
		$bannerID = $key1;
		$fromData = 1;

		if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$value1))
		$bannerpath='../'.DATA_DIR.'/'.$aid.'/'.$value1;
	}
	else
	{
		$bannerID = $value1['id'];
		$fromData = 0;

		$currentimage=$this->get_banner_name($aid,$value1['banner_size']);
		if($currentimage !="")
		{
			if(file_exists('../'.DATA_DIR.'/banners/'.$aid.'/'.$currentimage))
			$bannerpath='../'.DATA_DIR.'/banners/'.$aid.'/'.$currentimage;
		}
	}
	?>

<?php if($bannerpath !=""){?>
<div <?php if($adp123 ==3 || $type ==12){?>id="div-dimension-<?php echo $bannerID;?>"<?php }?> style="margin: 5px;">
<div style="white-space: nowrap;cursor: pointer;margin: 10px 0px;">
<bdi>
<?php
if($type ==14)
{
	$array=explode('_',$value1);

	if(isset($array[0]) && isset($array[1]))
	echo $array[0].' x '.$array[1];
}
else if($adp123 ==3)
{
    echo str_replace("-", " x ",$this->get_banner_dimension($key1));
}
else
echo $value1['width'].' x '.$value1['height'];
?>

<?php if($adp123 ==3 || $type ==12){?>

<?php if($val['banner_id'] != $bannerID){?>
<i class="fa fa-times" onclick="DeleteImage('<?php echo $bannerID;?>',<?php echo $fromData;?>);" title="<?php echo $this->get_label('delete');?>" alt="<?php echo $this->get_label('delete');?>"></i>

<span id="load<?php echo $bannerID;?>" style="display: none;position: absolute;margin-left: 5px;"><img src="images/load.gif"/></span>
<?php }} ?>
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
<td style="width: 450px;height: 20px;"><?php echo $this->get_label('name');?> : <?php echo $val['name'];?>

&nbsp;&nbsp;&nbsp;
<?php if($type==2 || $type ==11){?>
<?php echo $this->get_label('banner dimension');?> : <?php echo $diamensions;?>
&nbsp;&nbsp;&nbsp;
<?php }?>
</td>
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
	if($premium_ad_enabled == 1)
	{
	    if($val['premium_ad'] == 0)
			{?>
	     <a href="<?php echo $this->make_url("ad/premium/".$aid."/1/".$frompg);?>"
	       onclick="return confirm('<?php echo $this->get_message('do you really want to set this ad as premium ad');?>')"
	       ><i class="fa fa-star" style="font-size: 16px;color:#8a8e8a;" title="<?php echo $this->get_label('make premium');?>"></i></a>
		 <?php }

		 if($val['premium_ad'] == 1){?>
			<a href="<?php echo $this->make_url("ad/premium/".$aid."/0/".$frompg);?>"
				onclick="return confirm('<?php echo $this->get_message('do you really want to remove premium status of this ad');?>')"
				><i class="fa fa-trash" style="font-size: 16px;color:#e60d53;" title="<?php echo $this->get_label('remove premium');?>"></i></a>
		 <?php }
	}
	?>


<?php
if($frompg !=4){
?>
<a class="link_button" href="<?php echo $this->make_url("ad/change_status/".$aid."/".$frompg);?>"><?php echo $this->get_label('operations');?></a>

<?php }?>
</td>

</tr>

<?php
}

?>
</table>
<?php if($type == 9 || $type == 21){?>
<script type="text/javascript">
function LoadPop(url)
{
	window.open(url,'_blank'); //New Tab
}
</script>
<?php }?>

<script type="text/javascript">
function DeleteImage(bannerID,fromData)
{
	if(confirm("<?php echo $this->get_message('do you really want to delete this ad banner');?>"))
	{
		$("#load"+bannerID).show();

		aid = <?php echo $aid;?>;

		dataparam="bannerID="+bannerID+"&aid="+aid+"&fromData="+fromData;

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
					message="<?php echo $this->get_message('ad banner delete success');?>";

					$('#div-dimension-'+bannerID).remove();
				}


				$('#delete-message').html(message);

				if(msg ==1)
				$('#delete-message').css('color','red');
				else
				$('#delete-message').css('color','green');

				$('#delete-message').show();


				$("#load"+bannerID).hide();

				setTimeout(function(){
					$('#delete-message').slideUp();
				},1500);
			}
		});
	}
}

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


function LoadHTML5Preview(srcpath)
{
	$('#button-html5-show').hide();
	$('#button-html5-hide').show();
	$('#iframe-html5').attr("src",srcpath);
	$('#iframe-html5-div').show();
	$('#iframe-loader').show();
}

function HideHTML5Preview()
{
	$('#button-html5-show').show();
	$('#button-html5-hide').hide();
	$('#iframe-html5-div').hide();
	$('#iframe-html5').attr("src","");
	$('#iframe-loader').hide();
}



function HidePreviewBox()
{
	if($('.previewSection').length > 0)
	{
		$('.previewSection').html('');
		$('.previewCloseDiv').hide();
		$(".previewSectionShow").show();
	}
}

function AdPreview()
{
    titleEnabled               = 0;
    descriptionEnabled         = 0;
    urlEnabled                 = 0;
    textimageSize 			   = 0;
    bannerName                 = "";

    titleText                  = "<?php echo $val['title']; ?>";
    descriptionText            = "<?php echo $val['description']; ?>";
    displayurlText             = "<?php echo $val['display_url']; ?>";
	buttonText 				   = "<?php echo $val['cta_button_text']; ?>";
	adType 					   = <?php echo $type; ?>;


	if(adType == 11)
	{
		textimageSize              = <?php echo $val['banner_id']; ?>;
		bannerName                 = "<?php echo $textimageName; ?>";
	}

    if(titleText != "")
   	titleEnabled           = 1;

    if(descriptionText != "")
   	descriptionEnabled     = 1;

    if(displayurlText != "")
   	urlEnabled             = 1;

    if((titleEnabled == 0 && descriptionEnabled == 0 && urlEnabled == 0) || (adType != 1 && adType != 11))
    {
        $('.previewSection').html("");
        return;
    }


    $("#loading").show();

    dataparam    = "adType="+adType+"&titleEnabled="+titleEnabled+"&descriptionEnabled="+descriptionEnabled+"&urlEnabled="+urlEnabled+"&titleText="+titleText+"&descriptionText="+descriptionText+"&displayurlText="+displayurlText+"&buttonText="+buttonText+
        "&textimageSize="+textimageSize+"&bannerName="+bannerName;

    var urlvalue = '<?php echo $this->make_url("ad/load_ad_preview");?>';

    $.ajax(
    {
        type: "POST",
        data: dataparam,
        url: urlvalue,
        success: function(message)
        {
            $("#loading").hide();
            $(".previewSectionShow").hide();

            $('.previewSection').show();
            $('.previewCloseDiv').show();

            $('.previewSection').html(message);
        }
    });
}
</script>
