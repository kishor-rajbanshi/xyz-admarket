<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>
<?php
$active_theme=$this->read_cookie_param('active_theme');
$pricing_status=$this->get_variable('pricing_status');
$test_cpm=$this->get_variable('test_cpm');
if($active_theme =="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css"/>
<?php 
$direction=$this->get_variable('direction');
if($direction ==1){?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>

<body>
<style type="text/css">   
.notific_pricing {margin-left:0px;}


<?php if($pricing_status ==1){?>

@media (min-width: 480px) and (max-width: 991px)
{
	.pricing-box-biv {width:50% !important;}
	
	.pricing_border {border-right:2px solid #CCCCCC;} 
}


<?php }?>
</style>

<?php $alert_msg=$this->get_variable('alert_msg');?>


<?php 

$adtype=$this->get_variable('adtype');
$adstatus=$this->get_variable('adstatus');
$parent_ad=$this->get_variable('parent_ad');

if($adtype !=7 || ($adtype ==7 && ($parent_ad >0 || ($parent_ad ==0 && $adstatus ==-2)))){?>
<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 5px;" id="budget_div">



<div class="checkbox_head"><?php echo $this->get_label('mange pricing');?></div>

<?php if($adtype !=7 || ($adtype ==7 && ($parent_ad >0 || ($parent_ad ==0 && $adstatus ==-2)))){?>
<div class="col-md-12 col-sm-12 col-xs-12 padding-side" style="margin-top: 5px;display: none;" id="button_div">
<div class="form-inline" style="min-height: 40px;">
<div class="alert alert-danger" role="alert"><?php echo $this->get_label('budget for this ad is not active');?></div>
</div></div>
<?php }?>



<div class="col-md-12 col-sm-12 col-xs-12 box_style pricing-box-outer-biv" style="margin-top: 10px;padding-bottom: 15px;margin-bottom: 20px;">


<?php if($adtype ==7 && $parent_ad ==0 && $adstatus ==-2){?>
<span class="notification" style="float: right;">* <?php echo $this->get_label('budget settings for each individual banner size need to be updated separately');?></span>
<?php }?>

<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 10px;">

<?php if($alert_msg !=''){?>
<div class="col-md-12 col-sm-12 col-xs-12" id="main_alert" style="color: red;">
<div class="col-md-12 col-sm-12 col-xs-12">
<bdi><?php echo $alert_msg;?></bdi>
</div>
</div>
<?php }?>
<?php   
$aid				= $this->get_variable('aid');
$expiry				= $this->get_result('expiry');
$ad_pricing			= $this->get_variable('pricing');
$suggest_value		= $this->get_variable('suggest_value');
$default_rate		= $this->get_variable('default_rate');
$total_ad_budget	= $this->get_variable('total_ad_budget');
$daily_budget		= $this->get_variable('daily_budget');


$test_cpa_default_rate=$this->get_variable('test_cpa_default_rate');
$test_cpa_total_rate=$this->get_variable('test_cpa_total_rate');
$test_cpm_daily_budget=$this->get_variable('test_cpm_daily_budget');






$ds_places=Configuration::get_instance()->read('decimal_place');

if($ad_pricing == 0)
{
	$pricing_rate=$this->get_label('cpc rate');
	$def_rate_label=$this->get_label('cpc rate label');
}
else if($ad_pricing == 1)
{
	$pricing_rate=$this->get_label('cpm rate');
	$def_rate_label=$this->get_label('cpm rate label');
}
else if($ad_pricing == 6)
{
	$pricing_rate=$this->get_label('cpa rate');
	$def_rate_label=$this->get_label('cpa rate label');
}
else if($ad_pricing == 13)
{
	$pricing_rate=$this->get_label('cpv rate');
	$def_rate_label=$this->get_label('cpv rate label');
}
else if($ad_pricing == 9)
{
	$pricing_rate=$this->get_label('pop rate');
	$def_rate_label=$this->get_label('pop rate label');
}
else if($ad_pricing == 12)
{
	$pricing_rate=$this->get_label('affiliate rate');
	$def_rate_label=$this->get_label('affiliate rate label');
}
$ad_type=$this->get_variable('ad_type');

if($test_cpm==0 && ($ad_type==0 || $ad_type==1 || $ad_type==6 || $ad_type==13 || $ad_type==9 || $ad_type==12)){?>
<div class="col-md-6 col-sm-6 col-xs-12 pricing_border pricing-box-biv" style="margin-top:1px;display: none;">
<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">

<?php echo $pricing_rate;?>

<span class="compulsory">*</span></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="default_rate" id="default_rate" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('default_rate');" value="<?php if($default_rate >0) { echo round($default_rate,$ds_places); }?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min rate is',array('x'=>$def_rate_label,'y'=>$this->get_money_format($this->get_variable('min_default_rate'))));?>]</bdi></div>

</div>


</div></div></div>

<?php if($ad_pricing !=3 && $ad_pricing != 12){ ?>
<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">
<?php 
echo $this->get_label('suggested rate');
?>
</label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="sugg_rate" id="sugg_rate" disabled="disabled" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" value="<?php echo $suggest_value;?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
</div></div></div>
<?php } ?>

<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">
<?php echo $this->get_label('ad total budget');?>

<span class="compulsory">*</span></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="ad_budget" id="ad_budget" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('ad_budget');" value="<?php if($total_ad_budget >0) { echo round($total_ad_budget,$ds_places); }?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min ad total budget is',array('x'=>$this->get_money_format($this->get_variable('min_ad_budget'))));?>]</bdi></div>

</div>
</div></div></div>



<?php if($ad_pricing != 6 && $ad_pricing != 12){ ?>

<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;"><bdi><?php echo $this->get_label('ad daily budget');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">



<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="daily_budget" id="daily_budget" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('daily_budget');" value="<?php  if($daily_budget >0) { echo round($daily_budget,$ds_places); }?>"/>
<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min ad daily budget is',array('x'=>$this->get_money_format($this->get_variable('min_daily_budget'))));?>]</bdi></div>
</div>
</div></div></div>
<?php } ?>

<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;"></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">


<div class="notification"><bdi>[<?php echo $this->get_label('supported decimal places',array('x'=>$ds_places));?>]</bdi></div>



<input type="hidden" name="pricing_status" id="pricing_status" value="<?php echo $pricing_status;?>" />


<input class="btn btn-primary btn-lg" type="button" name="update" id="update_btn" style="display: none;" value="<?php echo $this->get_label('update');?>" onclick="update_budget(1);">
<input class="btn btn-primary btn-lg" type="button" name="add" id="add_btn" style="display: none;" value="<?php echo $this->get_label('add');?>" onclick="update_budget(2);" >

<span id="loader" style="padding-left:5px;display:none;"><img src="images/load.gif"></span>
</div>
</div>
</div>
</div>

<?php }else{ 
    if($ad_type==6)
    {
 $ad_pricing=6;
 if($ad_pricing == 6)
{
    $pricing_rate=$this->get_label('cpa rate');
    $def_rate_label=$this->get_label('cpa rate label');
    $min_default_rate=$this->get_minrate_by_uid($uid,6);
    $banner_id=$this->get_variable('banner_id');
    $suggest_value=$this->get_suggested_value_ad($adtype,$aid,$banner_id,6);
    $min_cpa_ad_budget=Configuration::get_instance()->read('min_cpa_total_budget');
}

?>


<div class="col-md-6 col-sm-6 col-xs-12 pricing_border pricing-box-biv" style="margin-top:1px;display: none;">
<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">

<?php echo $this->get_label('cpa rate');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)

<span class="compulsory">*</span></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="cpa_rate" id="cpa_rate"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('cpa_rate');" value="<?php if($test_cpa_default_rate >0) { echo round($test_cpa_default_rate,$ds_places);}?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php } ?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min rate is',array('x'=>$def_rate_label,'y'=>$this->get_money_format($min_default_rate)));?>]</bdi></div>

</div>


</div></div></div>



<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">
<?php echo $this->get_label('cpa total budget');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)

<span class="compulsory">*</span></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="cpm_ad_budget" id="cpm_ad_budget"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('cpm_ad_budget');" value="<?php if($test_cpa_total_rate>0){ echo round($test_cpa_total_rate,$ds_places);} ?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min ad total budget is',array('x'=>$this->get_money_format($this->get_variable('min_ad_budget'))));?>]</bdi></div>

</div>
</div></div></div>



<?php if($ad_pricing !=3 && $ad_pricing != 12){ ?>
<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">
<?php 
echo $this->get_label('suggested rate');
?>
</label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="sugg_rate" id="sugg_rate" disabled="disabled" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" value="<?php echo $suggest_value;?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
</div>
</div></div></div>
<?php } ?>




<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;"><bdi><?php echo $this->get_label('test cpm daily budget');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">



<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="daily_budget" id="daily_budget" <?php if($pricing_status==1){?> readonly <?php }?> onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('daily_budget');" value="<?php if($test_cpm_daily_budget >0) { echo round($test_cpm_daily_budget,$ds_places); }?>"/>
<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min ad daily budget is',array('x'=>$this->get_money_format($this->get_variable('min_daily_budget'))));?>]</bdi></div>
</div>
</div></div></div>





<!--<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;">
<?php echo $this->get_label('cpa total budget');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)

<span class="compulsory">*</span></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">
<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="cpm_ad_budget" id="cpm_ad_budget"  onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('cpm_ad_budget');" value="<?php if($test_cpa_total_rate>0){ echo round($test_cpa_total_rate,$ds_places);} ?>" />

<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min ad total budget is',array('x'=>$this->get_money_format($min_cpa_ad_budget)));?>]</bdi></div>

</div>
</div></div></div>-->








<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;"><bdi><?php echo $this->get_label('test cpm total budget');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">



<div>
<?php if(Configuration::get_instance()->read('currency_position')==1){?>
<span class="dollar_style" ><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>

<input class="form-control dollar_input" type="text" name="test_cpm_budget" id="test_cpm_budget" <?php if($pricing_status==1){?> readonly <?php }?> onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');CheckDecimalPlaces('test_cpm_budget');" value="<?php  if($total_ad_budget >0) { echo round($total_ad_budget,$ds_places); }?>"/>
<?php if(Configuration::get_instance()->read('currency_position')!=1){?>
<span class="dollar_style"><?php echo Configuration::get_instance()->read('currency_symbol');?></span>
<?php }?>
<div class="notific_pricing"><bdi>[<?php echo $this->get_label('min cpm budget is',array('x'=>$this->get_money_format($this->get_variable('min_ad_budget'))));?>]</bdi></div></div>
</div></div></div>


<div class="form-inline" style="min-height: 50px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-5 col-sm-5 col-xs-12" style="padding-left: 0px;"></label>
<div class="col-md-7 col-sm-7 col-xs-12" style="padding-left: 0px;">





<input type="hidden" name="pricing_status" id="pricing_status" value="<?php echo $pricing_status;?>" />


<input class="btn btn-primary btn-lg" type="button" name="update" id="update_btn" style="display: none;" value="<?php echo $this->get_label('update');?>" onclick="update_budget(1);">
<input class="btn btn-primary btn-lg" type="button" name="add" id="add_btn" style="display: none;" value="<?php echo $this->get_label('add');?>" onclick="update_budget(2);" >

<span id="loader" style="padding-left:5px;display:none;"><img src="images/load.gif"></span>
</div>
</div>
</div>
</div>
<?php }}?>





<div class="col-md-6 col-sm-6 col-xs-12 pricing-box-biv" id="budget_details" style="display: none;">
<div class="form-inline" style="min-height: 45px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-5 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('pricing status');?></label>
<div class="col-md-8 col-sm-7 col-xs-12" style="padding-left: 0px;">
<bdi id="status_div">
<?php if($pricing_status == 1) echo $this->get_label('active'); 
 else if($pricing_status == 2) echo $this->get_label('completed');
 else if($pricing_status == -1) echo $this->get_label('pending');
 else if($pricing_status == 0) echo $this->get_label('cancelled');
?>
</bdi> 


<span style="padding-left:10px;" id="price_btn">
<?php if($adtype !=7 || ($adtype ==7 && $parent_ad >0)){?>
<input class="btn btn-primary btn-lg" type="button" name="reset" value="<?php echo $this->get_label('budget cancel');?>" onclick="change_pricing_status();" >
<img style="display:none;padding-left:10px;" id="loader2" src="images/load.gif"> 
<?php }?>
</span>


</div>



<label class="col-md-4 col-sm-5 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('test mode status');?></label>
<div class="col-md-8 col-sm-7 col-xs-12" style="padding-left: 0px;">
<bdi id="status_div">
<?php if($test_cpm == 1) echo $this->get_label('test mode active'); 
       else  echo $this->get_label('test mode inactive');
?>
</bdi> </div>







</div></div>


<div class="form-inline" style="min-height: 40px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-5 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('ad budget used');?></label>
<div class="col-md-8 col-sm-7 col-xs-12" style="padding-left: 0px;">

<bdi><span id="used-budget-span"><?php echo $this->get_money_format($this->get_variable('total_budget_used'));?></span></bdi>

</div></div></div>

<?php if($ad_pricing != 6 && $ad_pricing != 12){ ?>
<div class="form-inline" style="min-height: 40px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-5 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('daily budget used');?></label>
<div class="col-md-8 col-sm-7 col-xs-12" style="padding-left: 0px;">

<bdi><span id="used-daily-budget-span"><?php echo $this->get_money_format($this->get_variable('daily_budget_used'));?></span></bdi>

</div></div></div>
<?php } ?>

<?php if($this->get_variable('start_date') >0){?>

<div class="form-inline" style="min-height: 40px;">
<div class="form-group col-md-12 col-sm-12 col-xs-12">
<label class="col-md-4 col-sm-5 col-xs-12" style="padding-left: 0px;"><?php echo $this->get_label('start date');?></label>
<div class="col-md-8 col-sm-7 col-xs-12" style="padding-left: 0px;">

<bdi id="start_time"><?php echo $this->get_date_format(2,$this->get_variable('start_date'));?></bdi>

</div></div></div>
<?php }?>
</div>


</div>
</div>

</div>
<?php }?>



<?php if($adtype ==7 && $parent_ad ==0 && $adstatus !=-2){?>
<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 5px;">
<div class="form-inline" style="min-height: 40px;">
<div class="alert alert-danger" role="alert"><?php echo $this->get_label('budget will be applied for each individual banner size separately');?></div>
</div></div>
<?php }?>


<input type="hidden" name="ac_balance" id="ac_balance" value="<?php echo $this->get_variable('ac_balance');?>" />
<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />
<input type="hidden" name="ad_pricing" id="ad_pricing" value="<?php echo $ad_pricing;?>" />
<input type="hidden" name="min_daily_budget" id="min_daily_budget" value="<?php echo $this->get_variable('min_daily_budget');?>" />
<?php if($test_cpm==1){?>

<input type="hidden" name="min_default_rate" id="min_default_rate" value="<?php echo Configuration::get_instance()->read('default_cpa_rate');?>" />
<input type="hidden" name="min_cpa_ad_budget" id="min_cpa_ad_budget" value="<?php echo Configuration::get_instance()->read('min_cpa_total_budget');?>" />
<input type="hidden" name="min_ad_budget" id="min_ad_budget" value="<?php echo $this->get_variable('min_ad_budget');?>" />
<input type="hidden" name="prev_cpa_budget" id="prev_cpa_budget" value="<?php echo$this->get_variable('test_cpa_total_rate')?>" />

<?php }else{?>
<input type="hidden" name="min_default_rate" id="min_default_rate" value="<?php echo $this->get_variable('min_default_rate');?>" />
<input type="hidden" name="min_ad_budget" id="min_ad_budget" value="<?php echo $this->get_variable('min_ad_budget');?>" />
<?php }?>
<input type="hidden" name="prev_budget" id="prev_budget" value="<?php echo $this->get_variable('total_ad_budget');?>" />
<input type="hidden" name="type" id="type" value="<?php echo $this->get_variable('type');?>"/>
<input type="hidden" name="test_cpm_stat" id="test_cpm_stat" value="<?php echo $this->get_variable('type');?>"/>

<div class="col-md-12 col-sm-12 col-xs-12" id="ad-run-history" style="margin-top: 10px;overflow: auto;"></div>


</body>
<script type="text/javascript">
$(document).ready(function()
{
	<?php if($adtype !=7 || ($adtype ==7 && ($parent_ad >0 || ($parent_ad ==0 && $adstatus ==-2)))){?>
	var pricing_status = $("#pricing_status").val();

	if(pricing_status == 1 || pricing_status == -1)
	{
		$(".pricing-box-outer-biv").show();
		$(".pricing-box-biv").show();

		if(pricing_status == 1)
		$("#budget_details").show();	
		else
		$("#budget_details").hide();	
	}
	else
	{
		$(".pricing-box-outer-biv").hide();
		$(".pricing-box-biv").hide();
		$("#budget_details").hide();
	}

	if(pricing_status == -1 || pricing_status == 0 || pricing_status == 2)
	{
		$(".pricing_border").css('border-right','0px');
				
		$("#update_btn").hide();
		$("#add_btn").show();
	}
	else if(pricing_status == 1)
	{
		$(".pricing_border").css('border-right','2px solid #CCCCCC');
		
		$("#add_btn").hide();
		$("#update_btn").show();
	}

	if(pricing_status == 0 || pricing_status == 2)
	$("#button_div").show();
	else
	$("#button_div").hide();


	if(pricing_status == 0 || pricing_status == 2)
	new_budget();

	<?php }?>


	LoadAdRunHistory();
	
	
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});

});


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



function update_budget(operation)
{  
	if(operation ==1)
	operation_message='<?php echo $this->get_message('budget update message');?>';
	else
	operation_message='<?php echo $this->get_message('budget add message');?>';	


	 var test_stat          		= <?php echo $test_cpm;?>;
     
	 if(test_stat==1)
	 {
			var ad_budget 	 		= $("#test_cpm_budget").val();
			var cpa_rate 		    = $("#cpa_rate").val();	
			var cpm_ad_budget 		= $("#cpm_ad_budget").val();
			var daily_budget 		= $("#daily_budget").val();
			var min_daily_budget 	= $("#min_daily_budget").val();
			var default_rate=0;
			cpa_rate=parseFloat(cpa_rate.trim());
			cpm_ad_budget=parseFloat(cpm_ad_budget.trim());
			daily_budget			= parseFloat(daily_budget.trim());
			min_daily_budget        = parseFloat(min_daily_budget.trim());
			var min_cpa_ad_budget =$("#min_cpa_ad_budget").val();
			min_cpa_ad_budget=parseFloat(min_cpa_ad_budget.trim());

                        var prev_cpa_budget=$("#prev_cpa_budget").val();
			prev_cpa_budget=parseFloat(prev_cpa_budget.trim());
     }
	 else
	 {
	  var ad_budget 	 	= $("#ad_budget").val();
	  var cpa_rate 		    = 0;
	  var cpm_ad_budget 	= 0;
	  var default_rate 		= $("#default_rate").val();
	  default_rate			= parseFloat(default_rate.trim());
		  
	 }
	var error_flag	 		= 0;  
	var aid          		= $("#aid").val();
	var pricing      		= $("#ad_pricing").val();
	var min_ad_budget 		= $("#min_ad_budget").val();
	var min_default_rate 	= $("#min_default_rate").val();	
	var pricing_status 		= $("#pricing_status").val();
	var prev_budget 		= $("#prev_budget").val();
	var ac_balance 			= $("#ac_balance").val();
	
	ad_budget				= parseFloat(ad_budget.trim());
	min_ad_budget			= parseFloat(min_ad_budget.trim());
	min_default_rate		= parseFloat(min_default_rate.trim());
	prev_budget				= parseFloat(prev_budget.trim());
    

	
	

	if(isNaN(ad_budget))
	ad_budget               = 0;

	if(isNaN(default_rate))
	default_rate            = 0;	

	if(isNaN(min_ad_budget))
	min_ad_budget            = 0;

	if(isNaN(min_default_rate))
	min_default_rate         = 0;


	if(isNaN(cpm_ad_budget))
		cpm_ad_budget=0; 

	if(isNaN(cpa_rate))
		cpa_rate=0; 
	
	
	
  if(test_stat==1)
  {
	   ad_budget_total=ad_budget+cpm_ad_budget;
  }
  else 
	  ad_budget_total=0; 

	
	if(pricing != 6 && pricing != 12)
	{     
		var daily_budget 		= $("#daily_budget").val();
		var min_daily_budget 	= $("#min_daily_budget").val();

		daily_budget			= parseFloat(daily_budget.trim());
		min_daily_budget        = parseFloat(min_daily_budget.trim());

		if(isNaN(daily_budget))
		daily_budget            = 0;

		if(isNaN(min_daily_budget))
		min_daily_budget        = 0;

	}
	else
	{ 
		if(test_stat !=1 )
		{
		var daily_budget 			= 0;
		var min_daily_budget 		= 0;
		}
	}



	if((ad_budget ==0 || cpm_ad_budget==0 || cpa_rate ==0 ||daily_budget==0) && test_stat==1 && pricing==6)
	{
	set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
		error_flag=1;
	}
	else if(ad_budget < prev_budget && pricing_status ==1 && test_stat==1 && pricing==6)
	{    
		set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>");
		error_flag=1;
	}
    else if(cpa_rate < min_default_rate && test_stat==1 && pricing==6)
	{  
		set_jnotice(0,"<?php echo $this->get_message('rate should be greater than minimum value',array('x'=>$def_rate_label)); ?>");
		error_flag=1;
	}	
    else if(cpm_ad_budget < min_cpa_ad_budget && test_stat==1 && pricing==6)
	{   
		set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than minimum value'); ?>");
		error_flag=1;
	}
	else if(test_stat==1 && daily_budget >0 && (daily_budget < min_daily_budget) && pricing==6)
	{   
		set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than minimum daily budget'); ?>");
		error_flag=1;
	}
	
	else if(test_stat==1 && (daily_budget > ad_budget) && pricing==6)
	{   
		set_jnotice(0,"<?php echo $this->get_message('daily budget should be less than test cpm budget'); ?>");
		error_flag=1;
	}
        else if(ad_budget < min_ad_budget && test_stat==1)
	{   
		set_jnotice(0,"<?php echo $this->get_message('test cpm budget should be greater than minimum value'); ?>");
		error_flag=1;
	}
	

       
	else if((ad_budget_total>ac_balance) && test_stat==1 && pricing==6)
	{
	set_jnotice(0,"<?php echo $this->get_message('sufficient balance not exists in your advertiser account'); ?>");
		error_flag=1;
	}
	
	else if((default_rate ==0 || ad_budget ==0) && test_stat==0)
	{
		set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
		error_flag=1;
	}
	else if(ad_budget > ac_balance)
	{     
		set_jnotice(0,"<?php echo $this->get_message('sufficient balance not exists in your advertiser account'); ?>");
		error_flag=1;
	}
	else if((default_rate < min_default_rate) && test_stat==0)
	{  
		set_jnotice(0,"<?php echo $this->get_message('rate should be greater than minimum value',array('x'=>$def_rate_label)); ?>");
		error_flag=1;
	}	
	else if(default_rate > ad_budget)
	{     
		set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than rate',array('x'=>$def_rate_label)); ?>");
		error_flag=1;
	}
	else if(ad_budget < min_ad_budget)
	{   
		set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than minimum value'); ?>");
		error_flag=1;
	}
	else if(ad_budget < prev_budget && pricing_status ==1 && test_stat==0)
	{    
		set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>");
		error_flag=1;
	}


        else if(cpm_ad_budget < prev_cpa_budget && pricing_status ==1 && test_stat==1)
	{    
		set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>");
		error_flag=1;
	}
	else if((pricing != 6 && pricing != 12) && daily_budget > ad_budget)
	{   
		set_jnotice(0,"<?php echo $this->get_message('daily budget should be less than ad budget'); ?>");
		error_flag=1;
	}
	else if(pricing != 6 && pricing != 12 && daily_budget >0 && daily_budget < min_daily_budget)
	{   
		set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than minimum daily budget'); ?>");
		error_flag=1;
	}
	else if(pricing !=6 && pricing != 12 && daily_budget >0 && daily_budget < default_rate) 
	{   
		set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than default rate',array('x'=>$def_rate_label));?>");
		error_flag=1;
	}
	
	    var parameter='aid='+aid+'&ad_budget='+ad_budget+'&default_rate='+default_rate+'&daily_budget='+daily_budget+'&cpa_rate='+cpa_rate+'&cpm_ad_budget='+cpm_ad_budget+'&test_stat='+test_stat;		
	  
   if(error_flag == 0 && confirm(operation_message))
   {
		$("#loader").show();
		$.ajax({
        type: "POST",
        url:"<?php echo $this->make_url("ad/update_pricing");?>",
		data:parameter,
		success: function(data)
		{
			$("#loader").hide(); 
        
			if(data=="error-1")
			set_jnotice(0,"<?php echo $this->get_message('mandatory'); ?>");
        	else if(data=="error-2")
        	set_jnotice(0,"<?php echo $this->get_message('account balance low'); ?>");         
    	    else if(data=="error-3")
    	   	set_jnotice(0,"<?php echo $this->get_message('rate should be greater than minimum value',array('x'=>$def_rate_label)); ?>");
            else if(data=="error-4")
           	set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than rate',array('x'=>$def_rate_label)); ?>"); 
            else if(data=="error-5")
           	set_jnotice(0,"<?php echo $this->get_message('ad budget should be greater than minimum value'); ?>");
            else if(data=="error-6")
{

           	set_jnotice(0,"<?php echo $this->get_message('you cannot decrement the ad budget'); ?>"); 
}          
            else if(data=="error-7")
           	set_jnotice(0,"<?php echo $this->get_message('daily budget should be less than ad budget'); ?>");
            else if(data=="error-8")
            set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than minimum daily budget'); ?>");
            else if(data=="error-9")
			set_jnotice(0,"<?php echo $this->get_message('daily budget should be greater than default rate',array('x'=>$def_rate_label));?>");
            else if(data=="error-10")
            set_jnotice(0,"<?php echo $this->get_message('error occurred'); ?>");
            else if(data=="error-11")
            set_jnotice(0,"<?php echo $this->get_message('invalid operation'); ?>");  
        	else
            {	
            	var jsonData = JSON.parse(data);
            	if(jsonData['success'] == 1)
            	{
            		$("#button_div").hide();   

            		$("#budget_details").show();
            		$(".pricing_border").css('border-right','2px solid #CCCCCC');
            		
            	
            		$("#adv_acc_balance", window.parent.document).html(jsonData['adv_balance_string']); 

                	$("#prev_budget").val(jsonData['ad_budget']); 
                	$("#pricing_status").val(jsonData['pricing_status']);


                	if(jsonData['pricing_status'] == 1)
                	{	
                		$("#add_btn").hide();
                    	$("#update_btn").show();
                    	
                		var msg='<?php echo $this->get_label("active");?>';

                    	$("#status_div").html(msg);

                    	if(jsonData['start_time'] != 0)
                		$("#start_time").html(jsonData['start_time']);
                	}

					<?php if($adstatus == -2){?>
              		set_jnotice(1,"<?php echo $this->get_message('you have successfully created new ad'); ?>"); 
					
					
					$("#draft-id", window.parent.document).hide();
					
					if(jsonData['ad_status'] == 1)
					$("#active-id", window.parent.document).show();
					else if(jsonData['ad_status'] == -1)
					$("#pending-id", window.parent.document).show();

                    <?php }else{?>
              		set_jnotice(1,"<?php echo $this->get_message('budget settings updated successfully'); ?>"); 
					<?php }?>
                }    
            }   
	         },
	         error: function(e)
	         {
		         $("#loader").hide();
		      	 alert("<?php echo $this->get_message("unable to process request")?>");
		         console.log(e);
	     	 }
	  	}); 
	}
}



function LoadAdRunHistory()
{
	var aid 			= $("#aid").val();

	$.ajax({
        type: "POST",
        url:"<?php echo $this->make_url("ad/run_history");?>",
		data:'aid='+aid,
		success: function(data)
		{
			$("#ad-run-history").html(data);	   
        },
        error: function(e)
        {
        	$("#loader").hide();
      	 	alert("<?php echo $this->get_message("unable to process request")?>");
            console.log(e);
        }
     }); 
}


function change_pricing_status()
{   
	var aid 			= $("#aid").val();
	var pricing_status 	= $("#pricing_status").val();

	if(pricing_status !=1)
	return;

	if(confirm('<?php echo $this->get_message('budget settings cancel');?>'))
	{
		$("#loader2").show(); 
		$.ajax({
	        type: "POST",
	        url:"<?php echo $this->make_url("ad/pricing_status");?>",
			data:'aid='+aid,
			success: function(data)
			{
				$("#loader2").hide(); 
				
				if(isJson(data))
				{
					var jsonData = JSON.parse(data);
	
	            	if(jsonData['success'] == 0)
	           		set_jnotice(0,"<?php echo $this->get_message('error occurred'); ?>");
	            	else if(jsonData['success'] == -1)
	           		set_jnotice(0,"<?php echo $this->get_message('invalid operation'); ?>");   
	            	else if(jsonData['success'] == 1)
	            	{
		        		$("#budget_div").hide();
						$("#button_div").show();
		
						$("#pricing_status").val(0);
		
						$("#ad_budget").val("");
						$("#default_rate").val("");

                        
                                                $("#cpa_rate").val("");
						$("#cpm_ad_budget").val("");
						$("#test_cpm_budget").val("");
						$('#daily_budget').prop('readonly', false);
						$('#test_cpm_budget').prop('readonly', false);
						
						if($("#daily_budget").length >0)
						$("#daily_budget").val("");
		
						$("#prev_budget").val(0);
		
		
						$("#used-budget-span").html("<?php echo $this->get_money_format(0);?>");
		
						if($("#used-daily-budget-span").length >0)
						$("#used-daily-budget-span").html("<?php echo $this->get_money_format(0);?>");
		
		
						$("#adv_acc_balance", window.parent.document).html(jsonData['content']); 

						set_jnotice(1,"<?php echo $this->get_message('pricing has been cancelled successfully'); ?>");

						LoadAdRunHistory();

						new_budget();
	            	}
				}
	         },
	         error: function(e)
	         {
	        	$("#loader2").hide();
	      	 	alert("<?php echo $this->get_message("unable to process request")?>");
	            console.log(e);
	         }
	     }); 
	}
}

function isJson(str) 
{
    try 
    {
        JSON.parse(str);
    } 
    catch (e) 
    {
        return false;
    }
    return true;
}

function new_budget()
{   
	var pricing_status=$("#pricing_status").val();

	if(pricing_status == 0)
	{
		$("#update_btn").hide();
		$("#add_btn").show();
	}
	

	$(".pricing_border").css('border-right','none');

	$(".pricing-box-outer-biv").show();
	$(".pricing-box-biv").show();

	$("#budget_div").show();
	$("#button_div").show();
	$("#budget_details").hide();

		
	var aid 				= $("#aid").val();  
	var min_default_rate 	= $("#min_default_rate").val();
	var min_ad_budget 		= $("#min_ad_budget").val();
	var min_daily_budget 	= $("#min_daily_budget").val();

}
</script>

<style type="text/css">
.btn-lg
{
	margin-top:0px !important;
}
</style>

</head>
</html>
