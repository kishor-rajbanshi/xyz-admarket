<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>


<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />


<?php

$aid=$this->get_variable('aid');

$res=$this->get_result('res');
$val=$res[0];
$type=$val['type'];
$banner=$val['banner'];

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


if($type ==2 || $type ==5 || $type ==7 || $type ==10 || $type ==11)
{
	$res1=$this->get_result('res1');
	$val1=$res1[0];
}



?>


<style type="text/css">
.modal-open {overflow: auto;}


<?php if($type ==2 || $type ==5 || $type ==7 || $type ==10){?>
.modal-dialog {width:<?php echo ($val1['width']+15);?>px;}
<?php }else if($type ==11){?>
.modal-dialog {width:300px;}
<?php }?>


.modal-content{border-radius: 0px;}

h2 {margin-top: 0px;}

*, *::before, *::after {box-sizing: unset !important;}

.ad-popup-div {margin-left: 0px;}
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
<?php if($type ==2 || $type ==5 || $type ==7){ ?>
<tr ><td colspan="6" style="border: 1px solid #CCCCCC;">

<?php if($type ==2 || $type ==5){?>

<div class="ad-popup" style="max-width: 150px;padding: 5px;">
<a href="<?php echo $val['click_url'];?>"><img style="max-width: 500px;max-height: 90px;" alt="<?php echo $this->get_label('banner');?>" src="<?php 
echo '../'.DATA_DIR.'/'.$aid.'_'.$banner;
?>" /></a>


<div class="ad-popup-div">
<?php if($expandable ==0){?>
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR.'/'.$aid.'_'.$banner;?>" />
<?php }else{?>
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR.'/'.$aid.'_exp_'.$expandable_banner;?>" />   
<?php }?>
</div>
</div>
<?php }else{?>

<div style="width: 95%;padding: 5px;min-height: 60px;margin-top: 10px;">

	<div class="layout-div-create" style="width: 100%;">
	<?php echo $this->get_display_layout_name($val['display_layout']);?>&nbsp;&nbsp;
	</div>
		
	<div class="layout-preview" id="layout-preview-<?php echo $val['display_layout'];?>" style="margin-top: 20px;">	
	<?php echo $this->get_display_preview($val['display_layout'],$aid,1);?>
	</div>

</div>



<?php }?>



</td></tr>


<?php }else if($type==1 || $type ==11){	?>
<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;border: 1px solid #CCCCCC;">



<table style="width: 100%;">
<tr>

<?php if($type ==11){?>
<td style="vertical-align: middle;width: <?php echo $val1['width'];?>px;padding-right: 2px;">
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR.'/'.$aid.'_'.$val['banner']; ?>" />
</td>
<?php }?>

<td style="height: 80px;">
<p><a href="<?php echo $val['click_url'];?>"><?php echo $val['title'];?></a></p>
<p><?php echo $val['description'];?></p>
<p><a href="<?php echo $val['click_url'];?>"><?php echo $val['display_url'];?></a></p>
</td>

</tr>
</table>
</td>
</tr>
<?php }else if($type==14){?>
<tr>
<td colspan="5" style="background-color: #ECECEC; padding: 5px;border: 1px solid #CCCCCC;">

<table style="width: 100%;">
<tr>
<td colspan="2">

<?php  
		$json_array=$this->get_array('json_array');
		
		$imagerow=$json_array[0];
		
		foreach($imagerow as $key1=>$value1)
		{
			$bannerpath='';
	
			if(file_exists('../'.DATA_DIR.'/'.$aid.'/'.$value1))
			$bannerpath='../'.DATA_DIR.'/'.$aid.'/'.$value1;
		
		    if($bannerpath !=""){?>
			<div class="col-md-12 col-sm-12 col-xs-12">
			<div style="white-space: nowrap;cursor: pointer;margin: 10px 0px;">
			<bdi>
			<?php 
			$array=explode('_',$value1);
				
			if(isset($array[0]) && isset($array[1]))
			echo $array[0].' x '.$array[1];
			?>
			</bdi>
			</div>

			<div>
			<img class="img-responsive" style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="<?php echo $bannerpath;?>" />
			</div>
			</div>
			<?php 
		    }
		}?>

</td>
</tr>
</table>
</td>
</tr>
<?php }?>
</table>
<script type="text/javascript">

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