<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />


<?php 
$aid=$this->get_variable('aid');
$uid=$this->get_variable('uid');
$adpriceing=$this->get_ad_pricing_value($aid);



$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>
</head>
<body>

<?php

$dif_cval=0;
if($adpriceing ==0)
$dif_cval=Configuration::get_instance()->read('min_click_value');
else if($adpriceing ==6)
$dif_cval=Configuration::get_instance()->read('default_cpa_rate');


$res=$this->get_result('res');
$value=$res[0];

$bannerid=$value['banner_id'];
$daily_budgets=$value['budget'];
$adtype=$value['type'];


$deviceenabled=$this->get_addon_status('device-targeting_enabled');
if($deviceenabled ==1)
$device=$value['device'];
else 
$device=0;	


if($daily_budgets =="")
$daily_budgets=0;


$alert_msg=$this->get_variable("alert_msg");
$act=$this->get_variable("act");

?>

<?php if(Configuration::get_instance()->read('keyword_based_ad_display') ==1){?>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="checkbox_head"><?php echo $this->get_label('add keywords');?></div>

<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div">


<div class="col-md-12 col-sm-12 col-xs-12" id="main_alert" style="color: red;"><bdi><?php echo $alert_msg;?></bdi></div>

<?php
$form=$this->create_form();
$form->start("keywords",$this->make_url("ad/keywords/".$aid),"post"); 
?>



<div class="col-md-5 col-sm-5 col-xs-12 padding-side" style="margin-top: 10px;">

<div class="form-inline">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-4 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('keywords');?></label>
<div class="col-md-8 col-sm-8 col-xs-12" style="padding-left: 0px;">
<textarea class="form-control" name="keywords" cols="40" style="height: 100px !important;"></textarea>
<div class="notification">[<?php echo $this->get_label('seperated by');?>]</div>
</div></div></div>

<div class="form-inline">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-sm-4 control-label" style="padding-left: 0px;"></label>
<div class="col-md-6" style="padding-left: 0px;">
<input type="hidden" name="uid" value="<?php echo $uid;?>">
<input type="hidden" name="aid" value="<?php echo $aid;?>">
<input class="btn btn-primary btn-lg" type="submit" name="keysubmit" value="<?php echo $this->get_label('add keywords');?>">
</div></div></div>



</div>
<?php $form->end(); ?>


</div></div></div>

<?php }?>


<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 10px;">
<div class="checkbox_head"><?php echo $this->get_label('manage targeted keywords');?></div>


<div class="col-md-12 col-sm-12 col-xs-12 padding-side" id="ajax_msg" style="height: 30px;"><?php if($act==1){echo $this->get_message("keyword deleted"); }?></div>



<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">
<td style="width: 300px;"><?php echo $this->get_label('keywords');?></td>
<td><?php echo $this->get_label('action');?></td>
</tr>


<?php 

$reslt=$this->get_result('reslt');
$pg=$this->get_variable("pg");

if(count($reslt)==0)
{
?>
<tr class="data_table_message"><td colspan="4" height="20px" class="frame_td_msg"><?php echo $this->get_label('global targeted');?></td></tr>
<?php 
	
}
else
{
foreach($reslt as $key=>$value1)
{
$kid=$value1['mid'];

?>

<tr class="data_table_content tr-row">
<td >
<input  type="text" name="keyword<?php echo $kid;?>" id="keyword<?php echo $kid;?>" value="<?php echo $value1['keyword'];?>" style="border:0;" readonly size="20">
<input type="hidden" name="keyword_dummy<?php echo $kid;?>" id="keyword_dummy<?php echo $kid;?>" value="<?php echo $value1['keyword']; ?>">
</td>
<td class="frame_td">
<input class="btn btn-primary btn-lg" type="button" id="edit<?php echo $kid;?>" name="edit<?php echo $kid;?>" value="<?php echo $this->get_label('edit');?>" onclick="editKeyword(<?php echo $kid;?>)">
<input class="btn btn-primary btn-lg" style="display: none;" type="button" id="cancel<?php echo $kid;?>" name="cancel<?php echo $kid;?>" value="<?php echo $this->get_label('cancel');?>" onclick="cancelKeyword(<?php echo $kid;?>)"> 
 
 
<a class="btn btn-primary btn-lg" href="<?php echo $this->make_url("ad/delete_keyword/".$kid."/".$aid."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this keyword');?>')"><?php echo $this->get_label('delete');?></a>

<span id="img<?php echo $kid;?>" style="display: none;vertical-align: bottom;"><img  src="images/load.gif" /></span>
</td>
</tr>

<?php }}?>
</table>
<input type="hidden" name="aid" id="aid" value="<?php echo $aid; ?>" />

<?php echo $this->get_variable('pagination');?>	
</div>

<div style="height: 10px;"></div>
<script language="Javascript" type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});

function trim(stringData)
{
return stringData.replace(/(^\s*|\s*$)/, "");
}

function cancelKeyword(kid)
{   
	if($(window).width() <768)
	var maintable="#table-desktop-mobile";
	else
	var maintable="#table-desktop";	

	$(maintable+" #edit"+kid).val("<?php echo $this->get_label('edit');?>");
	$(maintable+" #cancel"+kid).hide();

	$(maintable+" #keyword"+kid).css('border',0);
	$(maintable+" #keyword"+kid).val($(maintable+" #keyword_dummy"+kid).val());
	$(maintable+" #keyword"+kid).attr('readonly', true);
	

	<?php if($adpriceing ==0 || $adpriceing ==6){?>
	$(maintable+" #click_value"+kid).css('border',0);
	$(maintable+" #click_value"+kid).val($(maintable+" #click_value_dummy"+kid).val());
	$(maintable+" #click_value"+kid).attr('readonly', true);
	<?php }?>
	
}

function editKeyword(kid) 
{  
	$('#main_alert').hide();

	if($(window).width() <768)
	var maintable="#table-desktop-mobile";
	else
	var maintable="#table-desktop";	

	if($(maintable+" #edit"+kid).val() =="<?php echo $this->get_label('edit');?>")
	{
		$(maintable+" #edit"+kid).val("<?php echo $this->get_label('update');?>");
		$(maintable+" #cancel"+kid).show();

		$(maintable+" #keyword"+kid).attr('readonly', false);
		$(maintable+" #keyword"+kid).css('border','1px solid #CCCCCC');
		$(maintable+" #keyword"+kid).focus();

	}
	else if($(maintable+" #edit"+kid).val() =="<?php echo $this->get_label('update');?>")
	{
		var keyword=trim($(maintable+" #keyword"+kid).val());
		var aid=$('#aid').val();

		if(keyword =="")
		{
			alert('<?php echo $this->get_message('mandatory'); ?>');
			$(maintable+" #keyword"+kid).focus();
			return;
		}
	
		

		$(maintable+" #img"+kid).show();
	

		  dataparam = "keyword="+keyword+"&kid="+kid+"&aid="+aid;

		  $.ajax(
				   {
						type: "POST",
						data: dataparam,
						url: "<?php echo $this->make_url("ad/edit_keyword")?>",
						success: function(msg)
						{
							returnmessage=msg;
							returnmessage1=returnmessage.split("_");
							
							$(maintable+" #img"+kid).hide();

							if(returnmessage1[0] !=1)
							{
								$('#ajax_msg').html(msg);
							
								$(maintable+" #edit"+kid).val("<?php echo $this->get_label('edit');?>");
								$(maintable+" #cancel"+kid).hide();
								
								$(maintable+" #keyword"+kid).css('border',0);
								$(maintable+" #keyword"+kid).attr('readonly', true);
								
								
							}
							else
							{
								$('#ajax_msg').html(returnmessage1[1]);
							}
						}
					});
		
	}
}

</script>

<style type="text/css">
.btn-primary{margin-top:0px;}
</style>
</body>
</html>