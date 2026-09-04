<?php 
$this->dispatch("layout/header/2/3/a");
$uid=$this->get_variable('uid');


$type=$this->get_variable('type');
$status=$this->get_variable('status');
$duration=$this->get_variable('duration');
$adpricing=$this->get_variable('adpricing');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$retargeting_enabled=$this->get_addon_status('retargeting_enabled');
$ecommerce_enabled=$this->get_variable('ecommerce_enabled');
$text_ads_enabled=$this->get_variable('text_ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
?>
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js' ></script>

<script type="text/javascript">
$(document).ready(function() {
	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php 
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());	
?>
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
	$('.custom-date-div').show();
	else
	{
		$('.custom-date-div').hide();

		$('#from_date').val('');
		$('#to_date').val('');
	}
}

$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>


<div class="container">
<h2 class="page_heading new_heading"><?php echo $this->get_label('statistics of your ads');?></h2>
<div class="page_heading-btm"></div>
</div>



<div class="container">
<?php 
if($from_date =='' && $duration ==7)
	$duration=1;

$form2=$this->create_form();
$form2->start("manageads",$this->make_url("ad/statistics"),"post");
?>
  
  
<div class="search_div" style="float: left;width: 100%;">
<div class="form-group search_div_items" style="width: 180px;">
<select class="form-control" name="duration" id="duration" style="width: 150px;">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
</div>


<div class="form-group custom-date-div search_div_items"> 
<input class="form-control" type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />  
&nbsp;
<input class="form-control" type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
</div>

  


<div class="form-group search_div_items">
<?php echo $this->get_pricing_box($adpricing,1);?>
</div>
  
<div class="form-group type-td search_div_items">
    <select class="form-control" name="type" id="type" style="width: 150px;">
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
</div>
<div class="form-group search_div_items">
    <select class="form-control" name="status" id="status" style="width: 150px;">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
 </div>
<div class="form-group search_div_items">
  <input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
</div>
<?php $form2->end(); ?> 	



<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
<tr class="data_table_head">

<td ><?php echo $this->get_label('id');?></td>
<td style="width: 150px;"><?php echo $this->get_label('name');?></td>
<td><?php echo $this->get_label('ad pricing');?></td>

<?php if($adpricing !=9 && $adpricing !=12 && $adpricing !=13){?>
<td><?php echo $this->get_label('type');?></td>
<?php }?>

<td><?php echo $this->get_label('status');?></td>

<?php if($adpricing !=12){?>
<td><?php echo $this->get_label('impressions');?></td>
<?php }?>

<?php if($adpricing !=9){?>
<td><?php echo $this->get_label('clicks');?></td>

<?php if($adpricing !=12){?>
<td><bdi><?php echo $this->get_label('ctr');?></bdi></td>

<?php }}?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('conversions');?></td>
<td><bdi><?php echo $this->get_label('conversion ratio');?></bdi></td>
<?php }?>
<td><bdi><?php echo $this->get_label('money spend');?>&nbsp;(<?php echo Configuration::get_instance()->read('currency_symbol');?>)</bdi></td>
<td><?php echo $this->get_label('action');?></td>
</tr>
<?php 
$res=$this->get_result('res');
if(count($res)==0){?>
<tr class="data_table_message"><td colspan="12"><?php echo $this->get_label('no ads found');?></td></tr>
<?php }else{

		


foreach($res as $key=>$value)
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
	
	if($value['type'] !=7)
	$aidvalue1=$adid;
	else if($value['type'] ==7)
	$aidvalue1=$gvalue[0];	
	
	
	$rowspan=1;	
	
	
	if($from_date !='')  // for custom date range
	$statistics=$this->get_adv_date_range_statistics($from_date,$to_date,$uid,$aidvalue1,0);
	else
	$statistics=$this->get_advertiser_statistics($duration,$uid,$aidvalue1,0);
	
	
	
	$priceingvalue=$value['display_type'];
	?>
<tr class="data_table_content">

<td rowspan="<?php echo ($ecommercecount*$rowspan);?>" <?php if($iii >0){?> style="display: none;" <?php }?> >
<?php 
if($retargeting_enabled ==1)
{
	if($value['retargeting'] ==1){?>
	<i class="fa fa-recycle" title="<?php echo $this->get_label('retargeting');?>"></i>
	<?php } else {?> 
	<div class="blank-div">&nbsp;</div>
	<?php }
}?>

<a href="<?php echo $this->make_url("ad/view/".$adid);?>"><?php echo $adid;?></a>
</td>

<td rowspan="<?php echo ($ecommercecount*$rowspan);?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php echo $value['name'];?></td>
<td rowspan="<?php echo ($ecommercecount*$rowspan);?>" <?php if($iii >0){?> style="display: none;" <?php }?>><?php echo $this->get_ad_pricing($adid);?></td>


<?php if($adpricing !=9 && $adpricing !=12 && $adpricing !=13){?>

<?php if($priceingvalue !=9 && $priceingvalue !=12 && $priceingvalue !=13){?>

<?php if($ecommerce_enabled ==1 && ($type ==0 || $type ==7)){?>
<?php if($value['type'] !=7){?>
<td ><?php echo $this->get_ad_type($value['type']);?></td>
<?php }else{?>
<td ><bdi><a href="<?php echo $this->make_url("ad/view/".$gvalue[0]);?>"><?php echo $this->get_ad_type($value['type']).' - '.$gvalue[2];?></a></bdi></td>
<?php }?>
<?php }else{?>
<td ><?php echo $this->get_ad_type($value['type']);?></td>
<?php }}else{?>
<td><?php echo $this->get_label('na');?></td>
<?php }}?>


<td ><?php echo $this->get_ad_status($value['status']);?></td>


<?php if($priceingvalue ==0){?>
<td ><?php echo $statistics['impression'];?></td>


<?php if($adpricing !=9){?>
<td ><?php echo $statistics['click'];?></td>
<td ><?php echo $statistics['ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><bdi><?php echo $this->get_number_format($statistics['money_spent']);?></bdi></td>
<?php }else if($priceingvalue ==1){?>
<td ><?php echo $statistics['cpm_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpm_click'];?></td>
<td ><?php echo $statistics['cpm_ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><bdi><?php echo $this->get_number_format($statistics['cpm_spend']);?></bdi></td>


<?php }else if($priceingvalue ==9){?>
<td ><?php echo $statistics['pop_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><bdi><?php echo $this->get_number_format($statistics['pop_spend']);?></bdi></td>






<?php }else if($priceingvalue ==6){?>
<td ><?php echo $statistics['cpa_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpa_click'];?></td>
<td ><?php echo $statistics['cpa_ctr'];?></td>
<?php }?>

<td><?php echo $statistics['cpa_conversion'];?></td>
<td><?php echo $statistics['cpa_ratio'];?></td>



<td><bdi><?php echo $this->get_number_format($statistics['cpa_spend']);?></bdi></td>



<?php }else if($priceingvalue ==12){?>

<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td ><?php echo $statistics['affiliate_click'];?></td>

<?php if($adpricing !=12){?>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>

<td><?php echo $statistics['affiliate_conversion'];?></td>
<td><?php echo $statistics['affiliate_ratio'];?></td>
<td><bdi><?php echo $this->get_number_format($statistics['affiliate_spend']);?></bdi></td>



<?php }else if($priceingvalue ==3){?>
<td ><?php echo $this->get_label('na');?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $this->get_label('na');?></td>
<td ><?php echo $this->get_label('na');?></td>
<?php }?>


<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>
<td ><?php echo $this->get_label('na')?></td>


<?php }else if($priceingvalue ==13){?>
<td ><?php echo $statistics['cpv_impression'];?></td>

<?php if($adpricing !=9){?>
<td ><?php echo $statistics['cpv_click'];?></td>
<td ><?php echo $statistics['cpv_ctr'];?></td>
<?php }?>

<?php if(($adpricing ==-1 || $adpricing ==6 || $adpricing ==12) && ($cpa_enabled ==1 || $affiliate_enabled ==1)){?>
<td><?php echo $this->get_label('na');?></td>
<td><?php echo $this->get_label('na');?></td>
<?php }?>

<td><bdi><?php echo $this->get_number_format($statistics['cpv_spend']);?></bdi></td>

<?php }?>


<?php if($value['type'] !=7){?>
<td><a href="<?php echo $this->make_url("ad/detailed_statistics/".$adid);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
<?php }else{?>
<td><a href="<?php echo $this->make_url("ad/detailed_statistics/".$gvalue[0]);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('detailed');?>"></i></a></td>
<?php }?>


</tr>
<?php 

$iii=$iii+1;
}
}
}
?>		
</table>

<?php echo $this->get_variable('pagination');?>

<div style="height: 20px;"></div>

</div>
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
<?php $this->dispatch("layout/footer");?>