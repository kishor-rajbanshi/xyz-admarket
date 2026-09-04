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
$sortBy 		 = $this->get_variable('sortBy');
$orderBy 		 = $this->get_variable('orderBy');

if($from_date != '' && $to_date == '')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

if($from_date == '' && $duration == 7)
$duration = 1;
?>
<div class="sub_menu_main"><?php echo $this->get_label('statistics of your sites');?></div>

<?php $this->dispatch("links/links/34");?>

<table style="width: 100%;" >
<tr><td colspan="2">
<div class="search_div">
<?php
$form=$this->create_form();
$form->start("report-filter","","post");
?>
<table class="search_div_table">
<tr>
<td>
<select name="duration" id="duration">
<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
</select>
&nbsp;&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" size="8" />
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" size="8" />
&nbsp;&nbsp;
</td>
<td>
<input type="hidden" name="sortBy" id="sortBy" value="<?php echo $sortBy; ?>" />
<input type="hidden" name="orderBy" id="orderBy" value="<?php echo $orderBy; ?>" /> 
<input type="submit" name="stat" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>

</div>
</td></tr>
<tr><td colspan="4" style="height: 10px;"></td></tr>
</table>

<?php
$reportResult = $this->get_array('reportResult');
$resultCount  = count($reportResult);
?>
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('site name');?></td>
<td ><?php echo $this->get_label('pricing');?></td>
<?php
if(isset($reportResult['heading']))
{
	foreach($reportResult['heading'] as $hkey => $hvalue)
	{
		if($hkey == "ctr" || $hkey == "ecpm" || $hkey == "conversionratio" || $hkey == 'spend' || $hkey == 'adminprofit')
		continue;

		?>
		<td <?php if($resultCount > 2){?>class="sortable"<?php } ?> data-type="<?php echo $hkey; ?>">
			<bdi><?php echo $this->get_label($hvalue);?></bdi>
			<?php if($resultCount > 2){?>
				<span class="sort-icons">
						<i class="fa fa-sort-asc <?php if($sortBy == $hkey && $orderBy == "asc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
						<i class="fa fa-sort-desc <?php if($sortBy == $hkey && $orderBy == "desc"){?>sort-selected<?php } ?>" data-column="<?php echo $hkey; ?>"></i>
				</span>
			<?php } ?>
		</td>
		<?php
	}
}
?>
</tr>
<?php if($resultCount == 2){?>
<tr><td colspan="12" style="padding-left: 5px;" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php
} else {

	foreach($reportResult as $key => $value)
	{
		if($key == "heading" || $key == "pagination")
		continue;

		$rowspan  = count($value['reportData']);
		$iii     = 0;

		foreach($value['reportData'] as $key1 => $value1)
		{
			?>
			<tr class="row_data_tr">
			<td <?php if($iii == 0){?> rowspan="<?php echo $rowspan;?>"<?php }else{?> style="display:none;" <?php } ?>>
				<a href="<?php echo $this->make_base_url("dispatch/category_targeting/22/".$value['id'],ADMIN_DIR);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
				&nbsp;
				<a href="<?php echo $this->make_base_url("report/adcodes/".$value['id'],ADMIN_DIR);?>"><?php echo $value['siteName'];?></a>
			</td>
			<td class="border-left"><?php echo $this->get_label($key1);?></td>
			<?php
			foreach($value1 as $key2=>$value2)
			{
			    if($key2 == "ctr" || $key2 == "ecpm" || $key2 == "conversionratio" || $key2 == 'spend' || $key2 == 'adminprofit')
			    continue;
				?>
				<td><bdi><?php echo $value2;?></bdi></td>
			<?php
			}
			?>
		</tr>
		<?php 
		$iii++;
		}
	}
	?>
	<?php if(isset($reportResult['pagination'])){?>
	<tr><td colspan="12" align="center"><?php echo html_entity_decode($reportResult['pagination']);?></td></tr>
	<?php } ?>
	<?php
}
?>
</table>