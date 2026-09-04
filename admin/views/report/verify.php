<?php 
$this->dispatch("layout/header/7/_74");


$reportResultAdvertiserDaily   = $this->get_array("reportResultAdvertiserDaily");
$reportResultAdvertiserMonthly = $this->get_array("reportResultAdvertiserMonthly");
$reportResultAdvertiserYearly  = $this->get_array("reportResultAdvertiserYearly");

$reportResultPublisherDaily    = $this->get_array("reportResultPublisherDaily");
$reportResultPublisherMonthly  = $this->get_array("reportResultPublisherMonthly");
$reportResultPublisherYearly   = $this->get_array("reportResultPublisherYearly");



$headArray['cpc'][0] 	= array("cpc imp","cpc clicks","cpc spend");
$headArray['cpc'][1] 	= array("impression","click","spend");



$headArray['cpm'][0] 	= array("cpm imp","cpm spend");
$headArray['cpm'][1] 	= array("impression","spend");



$headArray['cpa'][0] 	= array("cpa imp","cpa conv","cpa spend");
$headArray['cpa'][1] 	= array("impression","conversion","spend");



$headArray['cpd'][0] 	= array("cpd imp","cpd spend");
$headArray['cpd'][1] 	= array("impression","spend");




$headArray['cpp'][0] 	= array("cpp imp","cpp spend");
$headArray['cpp'][1] 	= array("impression","spend");

$currency_symbol = Configuration::get_instance()->read('currency_symbol');
?>

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

<div class="sub_menu_main"><?php echo $this->get_label('check statistics');?></div>

<?php $this->dispatch("links/links/25");?>

<div class="inner-box">
<div class="report_div">

<table class="verify-box" style="width: 100%;" cellpadding="0" cellspacing="0">

  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('daily statistics');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('monthly statistics');?></td>
    <td onclick="show_pub_stat(3);" id="adustat3" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('yearly statistics');?></td>
    <td class="adustatclass"></td>
  </tr>


<tr id="showstat1" class="showadustatclass statistics_tr">
<td colspan="4" style="padding: 5px;" class="statistics_td">
  
<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >

<?php 
$iindex = 0;

foreach($reportResultAdvertiserDaily as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	?> 

		<td style="padding-left: 10px;width: 50px;"><bdi><?php echo $this->get_label('date');?></bdi></td>
		<td style="width: 30px;"></td>

    	<?php 
    	$iindex++;
    	continue;
    }

    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][0]))
		{
			$elementCount = count($headArray[$r1key][0]);
			$elementIndex = 0;

			foreach($headArray[$r1key][0] as $hkey => $hvalue)
			{
				$elementIndex++;

				?>
				<td style="width: 150px;">
					<bdi><?php echo $this->get_label($hvalue);?></bdi>

					<?php if($elementCount == $elementIndex){?>
					(<?php echo $currency_symbol; ?>)
					<?php } ?>

				</td>
				<?php 
			}
		}
	}
	
	$iindex++;

	if($iindex == 2)
	break;
}
?>
</tr> 


<?php 
$iindex = 0;

foreach($reportResultAdvertiserDaily as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	$iindex++;
    	continue;
    }


    ?>
    <tr class="row_data_tr">		
	<td rowspan="2"><?php echo $rkey;?></td>
	<td class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	<?php 
    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<tr class="row_data_tr">
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>

	<?php 
    foreach($reportResultPublisherDaily[$rkey] as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<?php 
	
	$iindex++;

}
?>
</table>  
</td>
</tr>  
  
<tr id="showstat2" class="showadustatclass statistics_tr">
<td colspan="4" style="padding: 5px;" class="statistics_td">  
  
<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >

<?php 
$iindex = 0;

foreach($reportResultAdvertiserMonthly as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	?> 

		<td style="padding-left: 10px;width: 50px;"><bdi><?php echo $this->get_label('month');?></bdi></td>
		<td style="width: 30px;"></td>

    	<?php 
    	$iindex++;
    	continue;
    }

    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][0]))
		{
			$elementCount = count($headArray[$r1key][0]);
			$elementIndex = 0;

			foreach($headArray[$r1key][0] as $hkey => $hvalue)
			{
				$elementIndex++;

				?>
				<td style="width: 150px;">
					<bdi><?php echo $this->get_label($hvalue);?></bdi>

					<?php if($elementCount == $elementIndex){?>
					(<?php echo $currency_symbol; ?>)
					<?php } ?>

				</td>
				<?php 
			}
		}
	}
	
	$iindex++;

	if($iindex == 2)
	break;
}
?>
</tr> 


<?php 
$iindex = 0;

foreach($reportResultAdvertiserMonthly as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	$iindex++;
    	continue;
    }


    ?>
    <tr class="row_data_tr">		
	<td rowspan="2"><?php echo $rkey;?></td>
	<td class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	<?php 
    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<tr class="row_data_tr">
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>

	<?php 
    foreach($reportResultPublisherMonthly[$rkey] as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<?php 
	
	$iindex++;

}
?>
</table>    
  
</td>
</tr> 


<tr id="showstat3" class="showadustatclass statistics_tr">
<td colspan="4" style="padding: 5px;" class="statistics_td"> 

<table class="data_table" cellpadding="0" cellspacing="0" style="width: 100%;">
<tr class="row_heading_tr" >

<?php 
$iindex = 0;

foreach($reportResultAdvertiserYearly as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	?> 

		<td style="padding-left: 10px;width: 50px;"><bdi><?php echo $this->get_label('year');?></bdi></td>
		<td style="width: 30px;"></td>

    	<?php 
    	$iindex++;
    	continue;
    }

    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][0]))
		{
			$elementCount = count($headArray[$r1key][0]);
			$elementIndex = 0;

			foreach($headArray[$r1key][0] as $hkey => $hvalue)
			{
				$elementIndex++;

				?>
				<td style="width: 150px;">
					<bdi><?php echo $this->get_label($hvalue);?></bdi>

					<?php if($elementCount == $elementIndex){?>
					(<?php echo $currency_symbol; ?>)
					<?php } ?>

				</td>
				<?php 
			}
		}
	}
	
	$iindex++;

	if($iindex == 2)
	break;
}
?>
</tr> 


<?php 
$iindex = 0;

foreach($reportResultAdvertiserYearly as $rkey => $rvalue)
{
    if($iindex == 0)
    {
    	$iindex++;
    	continue;
    }


    ?>
    <tr class="row_data_tr">		
	<td rowspan="2"><?php echo $rkey;?></td>
	<td class="adv-box"><p><?php echo $this->get_label('adv');?></p></td>
	<?php 
    foreach($rvalue as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<tr class="row_data_tr">
	<td class="pub-box"><p><?php echo $this->get_label('pub');?></p></td>

	<?php 
    foreach($reportResultPublisherYearly[$rkey] as $r1key => $r1value)
	{
		if(isset($headArray[$r1key][1]))
		{
			foreach($headArray[$r1key][1] as $hkey => $hvalue)
			{
				if(isset($r1value[$hvalue])){?>
				<td><bdi><?php echo $r1value[$hvalue];?></bdi></td>
				<?php 
				}
			}
		}
	}
	?> 
	</tr>

	<?php 
	
	$iindex++;

}
?>
</table>   

</td>
</tr>
</table> 

</div> 
</div>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_pub_stat(1);
</script>