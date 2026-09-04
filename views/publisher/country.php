<div class="report-div-tab report-div-tab-2">

<?php
$uid 		= $this->get_variable('uid');
$duration 	= $this->get_variable('duration');
$from_date 	= $this->get_variable('from_date');
$to_date 	= $this->get_variable('to_date');

$reportResult      = $this->get_array("reportResult");
$reportResultCount = count($reportResult);
?>
<table id="table-desktop-2" class="data_table" cellpadding="0" cellspacing="0">
<?php
foreach($reportResult as $rkey => $rvalue)
{
    if($rkey == 'heading')
    {?>
		<tr class="data_table_head">
		<?php
		$i = 1;
		foreach ($rvalue as $rkey1 => $rvalue1)
		{?>
			<td><bdi><?php echo $rvalue1;?></bdi></td>

			<?php if($i == 1){?>
				<td><bdi><?php echo $this->get_label("pricing");?></bdi></td>
			<?php }

			$i++;
		}
		?>
		</tr>
		<?php
		if($reportResultCount == 1)
		{
			?>
			<tr class="data_table_content">
				<td colspan="<?php echo $i;?>">
					<?php echo $this->get_label('no records found');?>
				</td>
			</tr>
			<?php
		}
	}
	else
	{
		$countryCode  = "";
		$countryName  = "";

		$countryArray = explode("::",$rkey);

		if(isset($countryArray[0]))
		$countryCode  = $countryArray[0];

		if(isset($countryArray[1]))
		$countryName  = $countryArray[1];

		$enterflag  = 0;
		$rowCount   = count($rvalue);

		if($rowCount > 2)
		$rowSpan = $rowCount;
		else
		$rowSpan = 1;

		foreach($rvalue as $rkey1 => $rvalue1)
		{
			if($rowCount <= 2 && $rkey1 == 'total')
		    	continue;

			?>
			<tr class="data_table_content">
			<td <?php if($enterflag == 0){?> rowspan="<?php echo $rowSpan;?>" <?php } else {?> style="display: none;"<?php } ?>>

				<?php if($countryCode != '0'){?>
				<img src="<?php echo BASE.'images/flags/'.strtolower($countryCode).'.png';?>" />
				<?php } ?>

				<bdi><?php echo $countryName;?></bdi>
			</td>

			<td class="border-start"><bdi><?php echo $this->get_label($rkey1);?></bdi></td>

			<?php foreach ($rvalue1 as $rkey11 => $rvalue11){?>
			<td><bdi><?php echo $rvalue11;?></bdi></td>
			<?php }?>

			</tr>

			<?php
			$enterflag++;
		}
	}
}
?>
</table>
</div>
