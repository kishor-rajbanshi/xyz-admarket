<script type="text/javascript">
function show_pub_stat(id)
{

	$('.showadustatclass').hide();
	$('.adustatclass').removeClass('tab-selection');
	
	$('#showstat'+id).show();
	$('#adustat'+id).addClass('tab-selection');

	$('#tab').val(id);
}
</script>	
<?php 
$duration=$this->get_variable('duration');
$tab=$this->get_variable('tab');

$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
?>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>

<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />

<script type="text/javascript" src="//www.google.com/jsapi"></script>
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

if($from_date !='')  // for custom date range
$statistics=$this->get_adv_date_range_timeperiod_statistics($from_date,$to_date);
else
$statistics=$this->get_advertiser_timeperiod_statistics($duration);

$datavalue='';
$earningvalue='';

if($duration ==7)
$hdata=',showTextEvery: 3';
else
$hdata=',showTextEvery: 1';


	foreach($statistics as $key=>$value)
	{
		 $year=substr($key,0,4);	
		 $month=substr($key,4,2);			
		 $day=substr($key,6,2);	
		 $hour=substr($key,8,2);		
		
	 
	 	 if($hour !="")
		 $data=$this->get_date_format(1,$year,$month,$day,$hour);
		 else 
		 $data=$this->get_date_format(1,$year,$month,$day);
	
		 
		 if($day=="")
		 $data=$this->get_date_format(1,$year,$month);

		 if($day=="" && $month=="")
		 $data=$year;	


		 
		 if($datavalue !='')
		 $datavalue.=',';
		 
		 
		 $sparray='';

		 $datavalue.="['".$data."'";		 
		 
		 if($cpc_enabled ==1)
		 {
		 	$datavalue.=",".$value['impression'].",".$value['click'].",".$value['ctr'];
		 
			$sparray.='2: {type: "line",targetAxisIndex:1}';
		 }		 
		 
		 
		 if($cpm_enabled ==1)
		 {
		 	$cpmearning=$value['cpm_spend']-$value['cpm_profit'];
		 	
		 	$datavalue.=",".$value['cpm_impression'].','.$value['cpm_click'].','.$value['cpm_ctr'];
		 	
		 	if($sparray !='')
		 	$sparray.=',5: {type: "line",targetAxisIndex:1}';
		 	else
		 	$sparray.='2: {type: "line",targetAxisIndex:1}';		 	
		 }
		 

		 
		 if($cpa_enabled ==1)
		 {
		 	$cpaearning=$value['cpa_spend']-$value['cpa_profit'];
		 
		 	$datavalue.=",".$value['cpa_impression'].','.$value['cpa_click'].','.$value['cpa_conversion'].','.$value['cpa_ratio'];
		 	
		 	if($sparray !='')
			{
				if($cpc_enabled ==1 && $cpm_enabled ==1)
		 		$sparray.=',9: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 || $cpm_enabled ==1)
				$sparray.=',6: {type: "line",targetAxisIndex:1}';
			}
		 	else
		 	$sparray.='3: {type: "line",targetAxisIndex:1}';
		 }
		 
		 if($html_enabled ==1)
	 	 $datavalue.=",".$value['html_impression'];
	 	 

		 if($pop_enabled ==1)
		 {
		 	$popearning=$value['pop_spend']-$value['pop_profit'];
		 	
		 	$datavalue.=",".$value['pop_impression'];
		 }
	 	 
	 	 
		 
		 
		 if($affiliate_enabled ==1)
		 {
		 	$affiliateearning=$value['affiliate_spend']-$value['affiliate_profit'];
		 	
		 	
		 	$datavalue.=','.$value['affiliate_click'].','.$value['affiliate_conversion'].','.$value['affiliate_ratio'];
		 	
		 	
		 	if($sparray !='')
	 		{
	 			if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',14: {type: "line",targetAxisIndex:1}';
	 			
	 			
				else if(($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $html_enabled ==1) || ($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1))
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';	 			
				else if(($cpc_enabled ==1 && $cpa_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1))
	 			$sparray.=',11: {type: "line",targetAxisIndex:1}';	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	 			
	 			 		
	 			
	 			else if(($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) || ($cpc_enabled ==1 && $cpa_enabled ==1 && $html_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1 && $html_enabled ==1))
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';
				else if(($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1) || ($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1))
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';	 			
				else if(($cpc_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1))
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';
				else if($cpa_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';				
	 			
	 			
	 			else if(($cpc_enabled ==1 && $pop_enabled ==1) || ($cpm_enabled ==1 && $pop_enabled ==1) || ($cpc_enabled ==1 && $html_enabled ==1) || ($cpm_enabled ==1 && $html_enabled ==1))
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	 			
	 			else if(($cpa_enabled ==1 && $pop_enabled ==1) || ($cpa_enabled ==1 && $html_enabled ==1))
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	 
	 			else if(($cpc_enabled ==1 && $cpa_enabled ==1) || ($cpm_enabled ==1 && $cpa_enabled ==1))
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 && $cpm_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';	 			
				else if($pop_enabled ==1 && $html_enabled ==1)
	 			$sparray.=',4: {type: "line",targetAxisIndex:1}';		 			
	 			
	 			
	 			else if($cpc_enabled ==1 || $cpm_enabled ==1)
	 			$sparray.=',5: {type: "line",targetAxisIndex:1}';
	 			else if($cpa_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';
	 			else if($pop_enabled ==1 || $html_enabled ==1)
	 			$sparray.=',3: {type: "line",targetAxisIndex:1}';	 			
	 			
	 		}
	 		else
		 	$sparray.='2: {type: "line",targetAxisIndex:1}';
		 }
		 
		 
		 if($cpv_enabled ==1)
		 {
		 	$cpvearning=$value['cpv_spend']-$value['cpv_profit'];
		 	
		 	$datavalue.=",".$value['cpv_impression'].','.$value['cpv_click'].','.$value['cpv_ctr'];
		 	
		 	if($sparray !='')
	 		{
	 			if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',17: {type: "line",targetAxisIndex:1}';
	 			
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',14: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',16: {type: "line",targetAxisIndex:1}';				
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';		 			
	 			else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',16: {type: "line",targetAxisIndex:1}';	
	 			else if($cpc_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',14: {type: "line",targetAxisIndex:1}';	
	 			else if($cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',14: {type: "line",targetAxisIndex:1}';		 			
				


	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',15: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';		
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';		 			
	 			
	 			
				else if($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	
				else if($cpc_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';		 			
				else if($cpc_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',11: {type: "line",targetAxisIndex:1}';	 			
				else if($html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',11: {type: "line",targetAxisIndex:1}';	 			
	 			
	 			
				else if($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';	
				else if($cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	
				else if($cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1) 
	 			$sparray.=',13: {type: "line",targetAxisIndex:1}';		 			
				else if($cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1) 
	 			$sparray.=',11: {type: "line",targetAxisIndex:1}';	 	 			
	 			
	 			
	 			
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $html_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';		 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',11: {type: "line",targetAxisIndex:1}';	 	 			
	 			
	 			

	 			
				else if($cpc_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	 			
				else if($cpc_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	 			
				else if($cpc_enabled ==1 && $html_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 			
	 			
	 			
	 			
	 			else if($cpc_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';
				else if($cpc_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';	
	
 			
				else if($cpc_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';		 			
	 			

	 			
	 			else if($cpm_enabled ==1 && $html_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';	 			
				else if($cpm_enabled ==1 && $html_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	 			
				else if($cpm_enabled ==1 && $html_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';		 			
	 			
	 			else if($cpm_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';
				else if($cpm_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',12: {type: "line",targetAxisIndex:1}';		 			
	 			
				else if($cpm_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';		 			
	 			
	 			
				else if($html_enabled ==1 && $cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';	 	 			
				else if($html_enabled ==1 && $cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';		 			
	 			
				else if($html_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	 			
	 			
				else if($cpa_enabled ==1 && $pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',10: {type: "line",targetAxisIndex:1}';		 			
	 			
	 			
				else if($cpc_enabled ==1 && $cpm_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';		 			
	 			else if($cpc_enabled ==1 && $html_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	
	 			else if($cpc_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 			 	 			
	 			else if($cpc_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	 
	 			else if($cpc_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';		 			

	 			else if($cpm_enabled ==1 && $html_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	 
	 			else if($cpm_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 	 						
	 			else if($cpm_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	 			
	 			else if($cpm_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';		 			
			
	 			
	 			else if($html_enabled ==1 && $cpa_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	
	 			else if($html_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',4: {type: "line",targetAxisIndex:1}';
	 			else if($html_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';	 			
	 			
	 			
	 			else if($cpa_enabled ==1 && $pop_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';	 
	 			else if($cpa_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',9: {type: "line",targetAxisIndex:1}';	 			
	 			
				else if($pop_enabled ==1 && $affiliate_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';		 			
	 			

	 			else if($cpc_enabled ==1 || $pop_enabled ==1 || $html_enabled ==1)
	 			$sparray.=',7: {type: "line",targetAxisIndex:1}';
	 			else if($cpm_enabled ==1 || $affiliate_enabled ==1)
	 			$sparray.=',8: {type: "line",targetAxisIndex:1}';	 
	 			else if($cpa_enabled ==1)
	 			$sparray.=',6: {type: "line",targetAxisIndex:1}';
	 		}
		 	else
		 	$sparray.='2: {type: "line",targetAxisIndex:1}';		 	
		 }		 
		 
		 
		 
		 
		 $datavalue.="]";
		 
			 
		 ///////////////////////////////
		 

		 
		 if($earningvalue !='')
		 $earningvalue.=',';
		 
		 
		 $earningvalue.="['".$data."'";		 
		 
		 if($cpc_enabled ==1)
		 {
			 $earning1=$value['money_spent']-$value['pub_profit'];
			 
			 if($earning1 >0 && $value['impression'] >0)
			 $ppcecpm=($earning1*1000)/$value['impression'];
			 else
			 $ppcecpm=0;
			 
			 $ppcecpm=round($ppcecpm,2);			 
			 
			 $earningvalue.=",".round($value['money_spent'],2).",".round($value['pub_profit'],2).",".round($earning1,2).",".$ppcecpm;
		 }

		 
		 if($cpm_enabled ==1)
		 {
		 	$cpmearning1=$value['cpm_spend']-$value['cpm_profit'];
		 
		 	$earningvalue.=",".round($value['cpm_spend'],2).','.round($value['cpm_profit'],2).','.round($cpmearning1,2);
		 }
		 
	 	 
	 	 if($cpa_enabled ==1)
		 {
		 	$cpaearning1=$value['cpa_spend']-$value['cpa_profit'];
		 
		 	$earningvalue.=",".round($value['cpa_spend'],2).','.round($value['cpa_profit'],2).','.round($cpaearning1,2);
		 }
	 	 
		 
		 if($html_enabled ==1)
	 	 $earningvalue.=",".$value['html_profit'];
	 	 
	 	 
	 	 
	 	 if($pop_enabled ==1)
		 {
		 	$popearning1=$value['pop_spend']-$value['pop_profit'];
		 
		 	$earningvalue.=",".round($value['pop_spend'],2).','.round($value['pop_profit'],2).','.round($popearning1,2);
		 }
		 
	 	 
		 
		 if($affiliate_enabled ==1)
		 {
		 	$affiliateearning1=$value['affiliate_spend']-$value['affiliate_profit'];
		 
		 	$earningvalue.=",".round($value['affiliate_spend'],2).','.round($value['affiliate_profit'],2).','.round($affiliateearning1,2);
		 }
		 
		 
		 if($cpv_enabled ==1)
		 {
		 	$cpvearning1=$value['cpv_spend']-$value['cpv_profit'];
		 
		 	$earningvalue.=",".round($value['cpv_spend'],2).','.round($value['cpv_profit'],2).','.round($cpvearning1,2);
		 }		 

		 	
		 $earningvalue.="]";
	 }
	 

	 
	 $datahead="['".$this->get_label('date')."'";
	 
	 
	 if($cpc_enabled ==1)
	 $datahead.=",'".$this->get_label('ppc impressions')."','".$this->get_label('ppc clicks')."','".$this->get_label('ppc ctr')."'";
	 
	 if($cpm_enabled ==1)
	 $datahead.=",'".$this->get_label('cpm impressions')."','".$this->get_label('cpm clicks')."','".$this->get_label('cpm ctr')."'";
	 
	 if($cpa_enabled ==1)
	 $datahead.=",'".$this->get_label('cpa impressions')."','".$this->get_label('cpa clicks')."','".$this->get_label('cpa conversions')."','".$this->get_label('conversion ratio')."'";
	
	 if($html_enabled ==1)
	 $datahead.=",'".$this->get_label('html impressions')."'";
	 
	 if($pop_enabled ==1)
	 $datahead.=",'".$this->get_label('pop impressions admin')."'";
	 
	 if($affiliate_enabled ==1)
	 $datahead.=",'".$this->get_label('affiliate clicks')."','".$this->get_label('affiliate conversions')."','".$this->get_label('affiliate conversion ratio')."'";
	 
	 if($cpv_enabled ==1)
	 $datahead.=",'".$this->get_label('cpv impressions')."','".$this->get_label('cpv clicks')."','".$this->get_label('cpv ctr')."'";
	 
	 
	 $datahead.="]";
	 
	 
	 
	 $currencyvariable=' ('.Configuration::get_instance()->read('currency_symbol').')';
	 
	 
	 $earninghead="['".$this->get_label('date')."'";
	 
	 if($cpc_enabled ==1)
	 $earninghead.=",'".$this->get_label('ppc spend').$currencyvariable."','".$this->get_label('ppc publisher profit').$currencyvariable."','".$this->get_label('ppc cash balance').$currencyvariable."','".$this->get_label('ppc ecpm')."'";
	 
	 if($cpm_enabled ==1)
	 $earninghead.=",'".$this->get_label('cpm spend').$currencyvariable."','".$this->get_label('cpm publisher profit').$currencyvariable."','".$this->get_label('cpm cash balance').$currencyvariable."'";
	 

	 if($cpa_enabled ==1)
	 $earninghead.=",'".$this->get_label('cpa spend').$currencyvariable."','".$this->get_label('cpa publisher profit').$currencyvariable."','".$this->get_label('cpa cash balance').$currencyvariable."'";
	 
	 
	 if($html_enabled ==1)
	 $earninghead.=",'".$this->get_label('html spend').$currencyvariable."'";
	 
	 if($pop_enabled ==1)
	 $earninghead.=",'".$this->get_label('pop spend admin').$currencyvariable."','".$this->get_label('pop publisher profit admin').$currencyvariable."','".$this->get_label('pop cash balance admin').$currencyvariable."'";
	 
	 if($affiliate_enabled ==1)
	 $earninghead.=",'".$this->get_label('affiliate spend').$currencyvariable."','".$this->get_label('affiliate publisher profit').$currencyvariable."','".$this->get_label('affiliate cash balance').$currencyvariable."'";
	 
	 if($cpv_enabled ==1)
	 $earninghead.=",'".$this->get_label('cpv spend').$currencyvariable."','".$this->get_label('cpv publisher profit').$currencyvariable."','".$this->get_label('cpv cash balance').$currencyvariable."'";
	 
	 
	 $earninghead.="]"; 
	 ?>



<script type="text/javascript">
var data;
var data1;
var options;
var options1;
var chart;
var chart1;
var chartArea;



google.load("visualization", "1", {packages:["corechart"]});
google.setOnLoadCallback(drawVisualization);


function drawVisualization() {
	
  data = google.visualization.arrayToDataTable([
    <?php echo $datahead;?>,
	<?php echo $datavalue; ?>
  ]);

  options = {

    vAxes: {viewWindow: {min: 0},0: {title:"<?php echo $this->get_label('count');?>",logScale: true, scaleType:"mirrorLog"},1: {maxValue: 100,title:"<?php echo $this->get_label('ctr');?>",logScale: true, scaleType:"mirrorLog"},gridlines: {count: 10}},
    
    hAxis: {slantedText:true,slantedTextAngle:60,title: "<?php echo $this->get_label('date');?>"<?php echo $hdata;?>,logScale:true},
    seriesType: "bars",
    series: {<?php echo $sparray;?>},
    animation:{
        duration: 1000,
        easing: 'in',
        startup: true
      },
    chartArea: {left:100,top:50,width:'85%'},
    legend:{position: 'top',textStyle: {fontSize: 10}}
  };

  chart = new google.visualization.ComboChart(document.getElementById('chart_div'));
  chart.draw(data, options);



  options1 = {
		    vAxis: {viewWindow: {min: 0},title: "<?php echo $this->get_label('earnings');?>",gridlines: {count: 10}},
		    hAxis: {slantedText:true,slantedTextAngle:60,title: "<?php echo $this->get_label('date');?>"<?php echo $hdata;?>},
		    seriesType: "line",
		    animation:{
		        duration: 1000,
		        easing: 'in',
		        startup: true
		      },
		    chartArea: {left:100,top:50,width:'100%'},
		    legend:{position: 'top',textStyle: {fontSize: 9}}
		  };

  data1 = google.visualization.arrayToDataTable([
                                                    <?php echo $earninghead;?>,
                                                	<?php echo $earningvalue; ?>
                                                  ]);



  chart1 = new google.visualization.ComboChart(document.getElementById('chart_div1'));
  chart1.draw(data1, options1);


  show_pub_stat(<?php echo $tab;?>);
}
</script>



<?php 
$geo_enabled=Configuration::get_instance()->read('countrywise_data_tracking');
$site_enabled=$this->get_addon_status('category-targeting_enabled');

$style_string='';

if($geo_enabled ==1 || $site_enabled ==1)
$style_string=' style="width: 99.6%;" ';
?>


<div class="inner-box home-box">
<div class="report_div">

<div class="toppers-head"><i class="fa fa-bar-chart" title="<?php echo $this->get_label('graphical reports');?>"></i><?php echo $this->get_label('graphical reports');?></div>



<table style="width: 100%;" >

  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 125px;"><?php echo $this->get_label('data graph');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 125px;"><?php echo $this->get_label('earnings graph');?></td>
    <td class="adustatclass">
    
<?php 
if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("overallstatistics","","post");
?>
<div style="float: right;margin-right: 5px;">
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />  
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
&nbsp;

<input type="hidden" name="tab" id="tab" value="1"/>
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</div>
<?php $form1->end(); ?> 
</td>
</tr>	

   


<tr class="statistics_tr"><td class="statistics_td" colspan="3" style="height: 20px;border-bottom: 0px;"></td></tr>

<tr id="showstat1" class="showadustatclass statistics_tr">
<td colspan="3" class="statistics_td">
<div id="chart_div" style="width: 100%; height: 413px;"></div>
</td>
</tr>

<tr id="showstat2" class="showadustatclass statistics_tr">
<td colspan="3" class="statistics_td">
<div id="chart_div1" style="width: 100%; height: 413px;"></div>
</td>
</tr>
</table>
</div> 
</div>