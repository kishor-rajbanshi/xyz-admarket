<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>


<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<script type="text/javascript">
$(document).ready(function() {

$('#duration').change(function()
{
	ShowHideDate();
});

ShowHideDate();
});

function ShowHideDate()
{
	if($('#duration').val() ==7)
	{
		$('#from_date').show();
		$('#to_date').show();
	}
	else
	{
		$('#from_date').hide();
		$('#to_date').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}
</script>
<?php 
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>


</head>
<body style="background: none;">

<table  class="iframe_table" cellpadding="0" cellspacing="0" border="0"  >


<tr><td height="10px"></td></tr>



		
<tr><td colspan="10">		
<?php 
		
$cpa_enabled=$this->get_addon_status('cpa_enabled');	
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');

$ecommerce_enabled=$this->get_variable('ecommerce_enabled');


$type=$this->get_variable('type');
$status=$this->get_variable('status');
$duration=$this->get_variable('duration');
$adpricing=$this->get_variable('adpricing');
$pg=$this->get_variable('pg');


$uid=$this->get_variable('uid');

if($from_date =='' && $duration ==7)
$duration=1;


$form2=$this->create_form();
$form2->start("manageads",$this->make_url("user/advadstat/".$uid),"post");
?>
 <div class="search_div" style="height: 40px;">
<table class="search_div_table" style="vertical-align: top;">
	 	

  
  <tr>
  <td style="width: 150px;">
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>

  </td>
    <td style="width: 140px;"> 
 
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date"  />  

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date"  />

  </td>
  
  <td style="width: 160px;">
  <?php echo $this->get_pricing_box($adpricing,1);?>
  </td>
  
    <td style="width: 160px;" class="type-td">    
    <select name="type" id="type">
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('allads');?></option>
    
    <?php if($text_ads_enabled ==1){?>    
    <option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
    <?php }?>
    
    <option value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>
    
    <?php if($textimage_enabled ==1){?>
    <option value="11" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
    <?php }?>    
    
    <?php if($ecommerce_enabled ==1){?>
    <option value="7" <?php if($type ==7) {echo "selected";}?>><?php echo $this->get_label('ecommerce ad');?></option>
    <?php }?>    
    
    <?php if($interstitial_enabled ==1){?>
    <option value="5" <?php if($type ==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
    <?php }?>     
    
    <?php if($skin_enabled ==1){?>
    <option value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
    <?php }?>
    </select>
    
   
    </td>
    <td style="width: 140px;">
    <select name="status" id="status">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select> 
	</td>
    <td> <input type="submit" name="search" class="link_button" value="<?php echo $this->get_label('go');?>" /></td>
    
    
    
  </tr>
  
	

</table>




</div>
 <?php $form2->end(); ?> 

</td></tr>	

<tr><td colspan="10" height="10px"></td></tr>

<tr><td colspan="10">
<table style="width: 99%;margin: 4px;" cellpadding="0" cellspacing="0" class="data_table_new">
		
<tr class="data_table_head">
<td width="120px"><?php echo $this->get_label('name');?></td>
<td width="100px"><?php echo $this->get_label('ad pricing');?></td>
<td width="80px"><?php echo $this->get_label('status');?></td>

<?php if($adpricing !=12){?>
<td width="80px"><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($adpricing !=9){?>
<td width="80px"><?php echo $this->get_label('clicks');?></td>

<?php if($adpricing !=12){?>
<td width="80px"><?php echo $this->get_label('ctr');?></td>
<?php }}?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('conversions');?></td>
<td><?php echo $this->get_label('conversion ratio');?></td>
<?php }?>


<td width="80px"><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('pubprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('yourprofit');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</td>
<td><?php echo $this->get_label('options');?></td>
</tr>


<?php 
$res11=$this->get_result('res11');

if(count($res11)==0)
{?>
<tr class="data_table_content"><td colspan="12" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
}


	
foreach($res11 as $key=>$value)
{

$adid=$value['id'];



	$ecommercearray=array();

	if($value['type'] ==7)
	$ecommercearray=$this->get_ecommerce_row_list($adid);


	$ecommercecount=count($ecommercearray);

	if($ecommercecount ==0)
	{
		$ecommercecount=1;
		$ecommercearray[]=0;
	}


$iii=0;

foreach($ecommercearray as $gkey=>$gvalue)
{
	$aidvalue1=0;

	if($value['type'] !=7 || $ecommercecount ==1)
	$aidvalue1=$adid;
	else if($value['type'] ==7)
	$aidvalue1=$gvalue[0];	
	
	
if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aidvalue1,0,0);
else
$statistics=$this->get_advertiser_statistics($duration,$uid,$aidvalue1,0,0);

$rowspan=1;


$pricing_value=$this->get_ad_pricing_value($adid);
?>
<tr class="data_table_content">
<td class="ad-popup" rowspan="<?php echo ($ecommercecount*$rowspan);?>" <?php if($iii >0){?> style="display: none;" <?php }?>>

<?php if($value['type'] !=7){?>
<a target="_parent" href="<?php echo $this->make_base_url("ad/view/".$adid,ADMIN_DIR);?>"><?php echo $value['name'];?></a>
<?php }else{?>
<?php echo $value['name'];?>
<?php }?>


<?php if($value['type'] ==7 && $ecommercecount >1){?>
<div style="margin-top: 10px;">
<a target="_parent" href="<?php echo $this->make_url("ad/change_status/".$adid."/3/".$duration."/".$type."/".$status."/0/".$adpricing."/".$pg);?>"><i class="fa fa-cogs settings-icon" style="color: #0190FE;" title="<?php echo $this->get_label('operations');?>"></i></a>
</div>
<?php }?>






<?php if($value['type'] !=0 && $value['type'] !=7 && $value['type'] !=14){?>
<div class="ad-popup-div">
<?php if($value['type'] ==1){?>

<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>

<?php }else if($value['type'] ==2 || $value['type'] ==5){?>

<img style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value['banner'];?>" />

<?php } else if($value['type'] ==11){?>



<table style="width: 100%;">
<tr>

<td style="vertical-align: middle;width: <?php echo $val1['width'];?>px;padding-right: 2px;border-bottom: 0px;">
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR;?>/<?php echo $adid;?>_<?php echo $value['banner'];?>" />
</td>


<td style="border-bottom: 0px;">
<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>
</td>
</tr> 
</table>

<?php }else if($value['type'] ==13){?>

<video width="300" height="225" controls >
<source src="<?php echo '../'.DATA_DIR.'/video/'.$value['id'].'/'.$value['banner'];?>" type="<?php echo $value['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>


<?php }?>
</div>
<?php }?>



</td>


<td <?php if($value['type'] ==7){?>class="border-left"<?php }?> ><?php echo $this->get_ad_pricing($adid);?>   

<?php if($ecommerce_enabled ==1 && $value['type'] ==7){?>
<div><a target="_parent" href="<?php echo $this->make_url("ad/view/".$gvalue[0]);?>"><?php echo $gvalue[2];?></a></div>
<?php }?>
</td>


<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($value['status']);?></td>


<?php if($pricing_value ==0){?>
<td><?php echo $statistics['impression'];?></td>


<?php if($adpricing !=9){?>
<td><?php echo $statistics['click'];?></td>
<td><?php echo $statistics['ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>





<td><?php echo $this->get_number_format($statistics['money_spent']);?></td>
<td><?php echo $this->get_number_format($statistics['pub_profit']);?></td>
<td><?php echo $this->get_number_format(($statistics['money_spent']-$statistics['pub_profit']));?></td>

<?php }else if($pricing_value ==1){?>

<td><?php echo $statistics['cpm_impression'];?></td>


<?php if($adpricing !=9){?>
<td><?php echo $statistics['cpm_click'];?></td>
<td><?php echo $statistics['cpm_ctr'];?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($statistics['cpm_spend']);?></td>
<td><?php echo $this->get_number_format($statistics['cpm_profit']);?></td>
<td><?php echo $this->get_number_format(($statistics['cpm_spend']-$statistics['cpm_profit']));?></td>


<?php }else if($pricing_value ==9){?>

<td><?php echo $statistics['pop_impression'];?></td>

<?php if($adpricing !=9){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($statistics['pop_spend']);?></td>
<td><?php echo $this->get_number_format($statistics['pop_profit']);?></td>
<td><?php echo $this->get_number_format(($statistics['pop_spend']-$statistics['pop_profit']));?></td>



<?php }else if($pricing_value ==6){?>
<td><?php echo $statistics['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td><?php echo $statistics['cpa_click'];?></td>
<td><?php echo $statistics['cpa_ctr'];?></td>
<?php }?>


<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>



<td ><?php echo $this->get_number_format($statistics['cpa_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['cpa_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['cpa_spend']-$statistics['cpa_profit']));?></td>




<?php }else if($pricing_value ==12){?>
<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><?php echo $statistics['affiliate_click'];?></td>

<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<td><?php echo $statistics['affiliate_conversion'];?></td>
<td><?php echo $statistics['affiliate_ratio'];?></td>



<td ><?php echo $this->get_number_format($statistics['affiliate_spend']);?></td>
<td ><?php echo $this->get_number_format($statistics['affiliate_profit']);?></td>
<td ><?php echo $this->get_number_format(($statistics['affiliate_spend']-$statistics['affiliate_profit']));?></td>


<?php }else if($pricing_value ==13){?>

<td><?php echo $statistics['cpv_impression'];?></td>


<?php if($adpricing !=9){?>
<td><?php echo $statistics['cpv_click'];?></td>
<td><?php echo $statistics['cpv_ctr'];?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_number_format($statistics['cpv_spend']);?></td>
<td><?php echo $this->get_number_format($statistics['cpv_profit']);?></td>
<td><?php echo $this->get_number_format(($statistics['cpv_spend']-$statistics['cpv_profit']));?></td>



<?php }else if($pricing_value ==3){?>

<td><?php echo $this->get_label('na');?></td>


<?php if($adpricing !=9){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>

<?php }?>

<td >

<?php 
if($value['type'] ==7 && $ecommercecount >1)
$aidvalue=$gvalue[0];
else
$aidvalue=$adid;
?>


<a target="_parent" href="<?php echo $this->make_url("ad/change_status/".$aidvalue."/3/".$duration."/".$type."/".$status."/0/".$adpricing."/".$pg);?>"><i class="fa fa-cogs settings-icon" title="<?php echo $this->get_label('operations');?>"></i></a></td>
</tr>



<?php 
$iii=$iii+1;
}
}?>	

<tr><td colspan="12" align="center"><?php echo $this->get_variable('pagination');?></td></tr>

</table>
</td></tr>	
</table>
<script type="text/javascript">
$(document).ready(function() {

$('#adpricing').change(function(){

LoadDropDown();
	
});


LoadDropDown();
	
});

function LoadDropDown()
{
	adpricing=$('#adpricing').val();

	if(adpricing ==9 || adpricing ==12 || adpricing ==13)
	{
		$('.type-td').hide();
		$('#type').val(0);
	}
	else
	$('.type-td').show();
}
</script>

</body>
</html>