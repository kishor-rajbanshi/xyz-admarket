<?php 	$this->dispatch("layout/header/7/_75");?>
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
$duration  = $this->get_variable('duration');
$from_date = $this->get_variable('from_date');
$to_date   = $this->get_variable('to_date');

if($from_date !='' && $to_date == '')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

if($from_date =='' && $duration ==7)
$duration=1;
?>
<div class="sub_menu_main"><?php echo $this->get_label('toppers list');?></div>

<?php $this->dispatch("links/links/25");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td colspan="4">

 <div class="search_div">
<table class="search_div_table">
<?php
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$html_enabled=$this->get_addon_status('html_enabled');
$category_enabled=$this->get_addon_status('category-targeting_enabled');
$sponsored_enabled=$this->get_addon_status('sponsored_enabled');
$cpp_enabled=$this->get_addon_status('cpp_enabled');



$top=$this->get_variable('top');
$sort=$this->get_variable('sort');


$form=$this->create_form();
$form->start("advstatistics","","post");
?>

<tr>
<td>
<select name="top" id="top" style="width: 125px;" onchange="LoadTopper(1);">
<option value="0" <?php if($top ==0) echo "selected";?>><?php echo $this->get_label('advertisers');?></option>
<option value="1" <?php if($top ==1) echo "selected";?>><?php echo $this->get_label('publishers');?></option>
<?php if($category_enabled ==1 && ($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpp_enabled ==1)){?>
<option value="2" <?php if($top ==2) echo "selected";?>><?php echo $this->get_label('sites');?></option>
<?php }?>
</select>
</td>
<td style="padding-left: 10px;">
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
<td>
&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="5" />
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="5" />
&nbsp;
</td>
<td>
<select name="sort" id="sort">
<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpp_enabled ==1){?>
<option value="0" <?php if($sort ==0) { echo "selected"; } ?>><?php echo $this->get_label('sort by impressions');?></option>
<?php }?>

<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpp_enabled ==1){?>
<option value="1" <?php if($sort ==1) { echo "selected"; } ?>><?php echo $this->get_label('sort by clicks');?></option>
<?php }?>


<option id="adv-data" value="2" <?php if($sort ==2) { echo "selected"; } ?>><?php echo $this->get_label('sort by money spend');?></option>
<option id="pub-data" value="3" <?php if($sort ==3) { echo "selected"; } ?>><?php echo $this->get_label('sort by pubprofit');?></option>

<?php if($cpa_enabled ==1){?>
<option value="4" <?php if($sort ==4) { echo "selected"; } ?>><?php echo $this->get_label('sort by conversions');?></option>
<?php }?>

</select>
</td>
<td>&nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
<?php $form->end(); ?>
</table>
</div>
</td></tr>
<tr><td colspan="4" style="height: 10px;"></td></tr>

<tr><td colspan="3">


<table class="data_table" cellpadding="0" cellspacing="0">
<?php
$i  = 1;
$ii = 1;


$reportResult      = $this->get_array("reportResult");
$reportResultCount = count($reportResult);

foreach($reportResult as $rkey => $rvalue)
{
    if($rkey == 'heading')
    {?>
		<tr class="row_heading_tr">
		<td><bdi><?php echo $this->get_label('no');?></bdi></td>
		<?php
		foreach ($rvalue as $rkey1 => $rvalue1)
		{
			if($rkey1 == "conversionratio")
			continue;
			?>
			<td><bdi><?php echo $rvalue1;?></bdi></td>
			<?php
			$i++;
		}
		?>
		</tr>
		<?php
		if($reportResultCount == 1)
		{
			?>
			<tr class="row_data_tr">
				<td colspan="<?php echo $i;?>">
					<?php echo $this->get_label('no records found');?>
				</td>
			</tr>
			<?php
		}
	}
	else
	{
		$userID    = 0;
		$userName  = "";
		$siteID    = "";
		$siteName  = "";

		$dataArray = explode("::",$rkey);

		if(isset($dataArray[0]))
		$userID    = $dataArray[0];

		if(isset($dataArray[1]))
		$userName  = $dataArray[1];

		if(isset($dataArray[2]))
		$siteID  = $dataArray[2];

		if(isset($dataArray[3]))
		$siteName  = $dataArray[3];

		foreach($rvalue as $rkey1 => $rvalue1)
		{
			if($rkey1 != 'total')
		    continue;

			?>
			<tr class="row_data_tr">
			<td><bdi><?php echo $ii;?></bdi></td>

			<?php if($top == 2){?>
			<td >
				<?php
					if($siteID > 0)
					{
						if($siteName != ""){?>
						<a href="<?php echo $this->make_url("dispatch/category_targeting/22/".$siteID);?>"><?php echo $siteName;?></a>
						<?php }else{?>
						<?php echo $this->get_label('deleted');?>
						<?php }
					}
					else
					echo $this->get_label('na');
				?>
			</td>
			<?php } ?>

			<td >
				<?php
				if($userID > 0)
				{
					if($userName !=""){?>
					<a href="<?php echo $this->make_url("user/profile/".$userID."/0");?>"><?php echo $userName;?></a>
					<?php }else{?>
					<?php echo $this->get_label('deleted');?>
					<?php }
				}else{ ?>
				<bdi><?php echo $userName;?></bdi>
				<?php } ?>
			</td>

			<?php
			foreach ($rvalue1 as $rkey11 => $rvalue11){

				if($rkey11 == "conversionratio")
				continue;

				?>
			<td><bdi><?php echo $rvalue11;?></bdi></td>
			<?php }?>

			</tr>
			<?php
			$ii++;
		}
	}
}
?>

</table>
</td></tr></table>

<script type="text/javascript">
function LoadTopper(from)
{
	if($('#top').val() ==0)
	{
		$('#adv-data').show();
		$('#pub-data').hide();
	}
	else if($('#top').val() ==1 || $('#top').val() ==2)
	{
		$('#adv-data').hide();
		$('#pub-data').show();
	}

	if(from ==1)
	{
		<?php if($cpc_enabled ==1 || $cpm_enabled ==1 || $html_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpp_enabled ==1){?>
		$('#sort').val(0);
		<?php }else if($cpc_enabled ==1 || $cpm_enabled ==1 || $cpa_enabled ==1 || $sponsored_enabled ==1 || $cpp_enabled ==1){?>
		$('#sort').val(1);
		<?php }?>
	}
}
LoadTopper(0);
</script>
<?php $this->dispatch("layout/footer");?>
