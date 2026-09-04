<?php
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



	$aid=$this->get_variable('aid');
	$frompg=$this->get_variable('frompg');
	$ad_pricing=$this->get_variable('ad_pricing');

$languageenabled=Configuration::get_instance()->read('language_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');


	$amount_spend=$this->get_variable('amount_spend');
	$amount_spend_today=$this->get_variable('amount_spend_today');
	$daily_budget=$this->get_variable('daily_budget');
	$total_ad_budget=$this->get_variable('total_ad_budget');



$adp123=$val['display_type'];

if($adp123 ==3)
{
			$spimpression=intval($this->get_variable('spimpression'));
			$spclick=intval($this->get_variable('spclick'));

}

$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');

?>
<style type="text/css">
.ad-popup-div {margin-left: 0px;}
</style>

<style type="text/css">
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
</style>

<?php if($type ==7){?>
<style type="text/css">
.modal-dialog {width:90%;margin:30px auto;}
.modal-body{max-height:700px;}

.data_table{text-align:center;}

.dummytitle{color: <?php echo $val['title_color'];?>;}
.dummydescription{color: <?php echo $val['desc_color'];?>;}
.dummyurl{color: <?php echo $val['url_color'];?>;}
.dummyprice{color: <?php echo $val['price_color'];?>;}
.dummyofferprice{color: <?php echo $val['offer_price_color'];?>;}

.dummybutton {color: <?php echo $val['ab_text_color'];?>;background-color: <?php echo $val['ab_background_color'];?>;}
.dummybutton:hover {background-color: <?php echo $val['ab_bhover_color'];?>;}

.span-text{color:<?php echo $val['ah_text_color'];?>;}

.span-button-preview{color:<?php echo $val['abh_text_color'];?>;background-color: <?php echo $val['abh_background_color'];?>;}
.span-button-preview:hover {background-color: <?php echo $val['abh_bhover_color'];?>;}

.display-outer-style{border-color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['background_color'];?>;}
.singleadsection-style{border-color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['ad_background_color'];?>;}

.slideleftinner-style, .sliderightinner-style {border-color:<?php echo $val['border_color'];?>;color:<?php echo $val['border_color'];?>;background-color: <?php echo $val['background_color'];?>;}

</style>


<?php }?>

<?php 
if($ad_pricing==0)
$pricing_rate=$this->get_label('cpc rate');
else if($ad_pricing==1)
$pricing_rate=$this->get_label('cpm rate');
else if($ad_pricing==6)
$pricing_rate=$this->get_label('cpa rate');
else if($ad_pricing==13)
$pricing_rate=$this->get_label('cpv rate');
else if($ad_pricing==9)
$pricing_rate=$this->get_label('pop rate');
else if($ad_pricing==12)
$pricing_rate=$this->get_label('affiliate rate');
	
?>


<div class="col-md-12 col-sm-12 col-xs-12 box_style new_box">
<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12 box_div box_div_bg" style="padding-top:10px;">

<div class="col-md-9 col-sm-9 col-xs-12 preview-div" style="margin-bottom: 5px;">

<?php if($type ==2 || $type ==5 || $type ==7 ){ ?>
<?php if($type ==2 || $type ==5){ ?>

<div class="col-md-12 col-sm-12 col-xs-12 padding-side ad-popup">
<a href="<?php echo $val['click_url'];?>"><img class="img-responsive"  alt="<?php echo $this->get_label('banner');?>" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $banner;?>" /></a>

<?php if($expandable ==1){?>
<div class="ad-popup-div col-md-12 col-sm-12 col-xs-12">
<img alt="<?php echo $this->get_label('banner');?>" class="img-responsive" src="<?php echo DATA_DIR;?>/<?php echo $aid;?>_exp_<?php echo $expandable_banner;?>" />
</div>
<?php }?>
</div>


<?php }else{?>


	<div class="layout-div-create" style="width: 100%;">
	<?php echo $this->get_display_layout_name($val['display_layout']);?>&nbsp;&nbsp;
	
	<span class="link_button show-span" onclick="LoadLayoutPreview(<?php echo $val['display_layout'];?>);"><?php echo $this->get_label('show preview');?></span>
	
	<span class="link_button hide-span" onclick="LoadLayoutPreview(0);"><?php echo $this->get_label('hide preview');?></span>
	
	
	</div>
		
	<div class="layout-preview" id="layout-preview-<?php echo $val['display_layout'];?>">	
	<?php echo $this->get_display_preview($val['display_layout'],$aid,0);?>
	</div>




<?php }}else if($type == 0 && $adp123 ==9){?>

<span><a style="cursor: pointer;" <?php if($pop_enabled ==1){?>onclick="LoadPop('<?php echo $val['click_url'];?>',<?php echo $val['pop_type'];?>);"<?php }?>><?php echo $val['click_url'];?></a></span>



<?php } else if(($type == 0 && $adp123 ==12) || $type ==14){
	
	
	if($type ==14)
	{
		$json_array=$this->get_array('json_array');
		
		$imagerow=$json_array[0];
	}
	else
	$imagerow=$this->get_result('imagerow');
	?>

	<span class="get_popup_btn link_button" data-toggle="modal" data-target="#Affiliate-Preview"><?php echo $this->get_label('preview');?></span>
	
	


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
      		
      		<div class="col-md-12 col-sm-12 col-xs-12">
      		<div id="delete-message" class="col-md-12 col-sm-12 col-xs-12" style="font-size: 14px;display: none;text-transform: none;"></div>
      		</div>
      		
							  	
<div class="col-md-12 col-sm-12 col-xs-12" style="overflow: auto;max-height: 450px;">

<?php if(count($imagerow) ==0){?>

<div class="col-md-12 col-sm-12 col-xs-12">
<?php echo $this->get_label('no banners found');?>
</div>
<?php }else{?>

<?php foreach($imagerow as $key1=>$value1){

	
	$bannerpath='';
	
	if($type ==14)
	{
		if(file_exists(DATA_DIR.'/'.$aid.'/'.$value1))
		$bannerpath=DATA_DIR.'/'.$aid.'/'.$value1;
	}	
	else
	{
		$currentimage=$this->get_banner_name($aid,$value1['banner_size']);
		if($currentimage !="")
		{
			if(file_exists(DATA_DIR.'/banners/'.$aid.'/'.$currentimage))
			$bannerpath=DATA_DIR.'/banners/'.$aid.'/'.$currentimage;
		}
	}
	
	?>

<?php if($bannerpath !=""){?>
<div class="col-md-12 col-sm-12 col-xs-12" <?php if($adp123 ==12){?>id="div-dimension-<?php echo $value1['id'];?>"<?php }?>>
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

<?php } else if($type ==1 || $type ==11){?>
<span style="vertical-align: top;">

<?php if($type == 11){

$textimagelink="";

if(file_exists(DATA_DIR.'/'.$val['id'].'_'.$val['banner']))
	$textimagelink=DATA_DIR.'/'.$val['id'].'_'.$val['banner'];
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


<?php }else if($type ==13){?>

<video width="300" height="225" controls >
<source src="<?php echo DATA_DIR.'/video/'.$val['id'].'/'.$val['banner'];?>" type="<?php echo $val['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

<?php }?>


</div>

<?php if($val['status'] !=-2){?>
<div class="col-md-3 col-sm-3 col-xs-12 preview-div click_div_bg">
<bdi>
<?php if($adp123 !=3){  ?>
<div><?php if($adp123 ==6 || $adp123 ==12)echo $this->get_label('conversion rate'); else echo $pricing_rate;?> : <?php echo $this->get_money_format($val['default_rate']);?></div>
<div><?php echo $this->get_label('budget');?> : <?php echo $this->get_money_format($amount_spend).' / '.$this->get_money_format($total_ad_budget);?></div>
<?php }
if($adp123 ==0 || $adp123 ==1 || $adp123 ==13 || $adp123 ==9){?>
<div><?php echo $this->get_label('daily budget');?> : <?php echo $this->get_money_format($amount_spend_today).' / '.$this->get_money_format($daily_budget);?></div>
<?php } 
if($adp123 ==3 && $sponsored_enabled ==1){?>
<div><?php echo $this->get_label('impressions');?> : <?php echo $spimpression;?></div>
<div><?php echo $this->get_label('clicks');?> : <?php echo $spclick;?></div>
<?php }?>
</bdi>
</div>
<?php }?>



</div>

</div>








<?php if($val['display_type'] ==6){
$basepath=str_replace('https://','',TRACK_BASE);
$basepath=str_replace('http://','',$basepath);
/*	
?>
 
<div class="col-md-12 col-sm-12 col-xs-12 padding-side">

<div style="cursor: pointer;float: right;margin-bottom: 5px;"><i class="fa fa-clipboard fa-lg" aria-hidden="true" id="trackingcode" title="<?php echo $this->get_label('copy adcode');?>"></i></div>

<textarea id="textarea-trackingcode" style="border: 1px solid #CCCCCC;width: 100%;min-height: 90px;" readonly="readonly">
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?> -->
<script data-cfasync="false" type="text/javascript" src="<?php echo '//'.$basepath.DISPLAY_DIR; ?>/index.php?page=click/conversion/<?php echo $aid; ?>"></script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?>  -->
</textarea>


<span class="notification"><?php echo $this->get_label('please copy conversion code');?></span>

</div>

 
 
 <?php */ ?>
 <div class="col-md-12 col-sm-12 col-xs-12">

<div style="cursor: pointer;margin-bottom: 5px;">
	<label><input type="radio" name="conversion_tracking" id="conversion_tracking1" value="1" checked="checked"><?php echo $this->get_label('conversion tracking code');?></label>

	<!-- <label><input  onclick ="show_code(2);"  type="radio" name="conversion_tracking" id="conversion_tracking2" value="2" ><?php echo $this->get_label('conversion tracking postback url');?></label>-->

</div>
<div id="dtrackingcode"><i  style="float: right;"  class="fa fa-clipboard fa-lg" aria-hidden="true" id="trackingcode1"   title="<?php echo $this->get_label('copy adcode');?>"></i>

<textarea id="textarea-trackingcode" style="border: 1px solid #CCCCCC;width: 100%;min-height: 90px;" readonly="readonly">
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?> -->
<script data-cfasync="false" type="text/javascript" src="<?php echo '//'.$basepath.TRACK_DIR; ?>/index.php?page=click/conversion/<?php echo $aid; ?>"></script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?>  -->
</textarea>
<span class="notification"><?php echo $this->get_label('please copy conversion code');?></span>

</div>



<!-- 
<div id="dtrackingurl" class="col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top:20px;display:none;">

	<i class="fa fa-clipboard fa-lg" aria-hidden="true" id="trackingcode2" style="float: right;"  title="<?php echo $this->get_label('copy adcode');?>"></i>
	
	<textarea id="textarea-trackingurl"  style="border: 1px solid #CCCCCC;width: 100%;" readonly="readonly" rows="1"><?php echo BASE.DISPLAY_DIR; ?>/index.php?page=click/conversion/<?php echo $aid; ?>
	</textarea>
</div>
-->


</div>
 
<?php }?>



<?php if($val['display_type'] ==12){
$basepath=str_replace('https://','',TRACK_BASE);
$basepath=str_replace('http://','',$basepath);
	
?>

<div class="col-md-12 col-sm-12 col-xs-12">

<div style="cursor: pointer;margin-bottom: 5px;">
	<label><input type="radio" name="conversion_tracking" id="conversion_tracking1" value="1" checked="checked"><?php echo $this->get_label('conversion tracking code');?></label>

	<!-- <label><input  onclick ="show_code(2);"  type="radio" name="conversion_tracking" id="conversion_tracking2" value="2" ><?php echo $this->get_label('conversion tracking postback url');?></label>-->


<div id="dtrackingcode"><i style="float: right;" class="fa fa-clipboard fa-lg" aria-hidden="true" id="trackingcode1"   title="<?php echo $this->get_label('copy adcode');?>"></i>

<textarea id="textarea-trackingcode" style="border: 1px solid #CCCCCC;width: 100%;min-height: 90px;" readonly="readonly">
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?> -->
<script data-cfasync="false" type="text/javascript" src="<?php echo '//'.$basepath.TRACK_DIR; ?>/index.php?page=click/conversion/<?php echo $aid; ?>"></script>
<!-- <?php  echo Configuration::get_instance()->read('admarket_name');?> - <?php echo $this->get_label('tracking code');?>  -->
</textarea>
<span class="notification"><?php echo $this->get_label('please copy conversion code');?></span>

</div>



<!-- 
<div id="dtrackingurl" class="col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top:20px;display:none;">

	<i class="fa fa-clipboard fa-lg" aria-hidden="true" style="float: right;" id="trackingcode2"   title="<?php echo $this->get_label('copy adcode');?>"></i>
	
	<textarea id="textarea-trackingurl"  style="border: 1px solid #CCCCCC;width: 100%;" readonly="readonly" rows="1"><?php echo BASE.DISPLAY_DIR; ?>/index.php?page=click/conversion/<?php echo $aid; ?>
	</textarea>
</div>
-->


</div>
</div>
<?php }?>









</div>

<?php if($pop_enabled ==1 && $adp123 ==9){?>
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

/*
function show_code(id)
{
	if(id==1)
	{
		$('#dtrackingcode').show();
		$('#dtrackingurl').hide();
		$('#conversion_tracking1').prop("checked",true);
		
	}
	else
	{
		$('#dtrackingcode').hide();
		$('#dtrackingurl').show();
		$('#conversion_tracking2').prop("checked",true);

	}
}

show_code(1);
*/


<?php if($val['display_type'] ==6 || $val['display_type'] ==12){?>
$("#trackingcode1").click(function(){
	   $("#textarea-trackingcode").select();
	   document.execCommand('copy');
	});
$("#trackingcode").click(function(){
	   $("#textarea-trackingcode").select();
	   document.execCommand('copy');
	});
$("#trackingcode2").click(function(){
	   $("#textarea-trackingurl").select();
	   document.execCommand('copy');
	});
<?php }?>


</script>
