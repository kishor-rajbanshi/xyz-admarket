<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>

<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<link rel="stylesheet" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>jquery-ui/jquery-ui.min.css" />

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
$pub       = $this->get_variable('pub');
$from_date = $this->get_variable('from_date');
$to_date   = $this->get_variable('to_date');

if($from_date != '' && $to_date == '')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

if($from_date == '' && $duration == 7)
$duration = 1;
?>
</head>
<body style="background: none;">
<table style="width: 100%;" >
<tr><td colspan="2" style="height: 20px;"></td></tr>
<tr><td colspan="2">
<div class="search_div">
<?php
$form=$this->create_form();
$form->start("showstatistics","","post");
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
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="8" />
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="8" />
&nbsp;&nbsp;
</td>
<td>
<input type="hidden" name="pub" id="pub" value="<?php echo $pub;?>" />
<input type="submit" name="stat" class="link_button" value="<?php echo $this->get_label('go');?>"/></td>
</tr>
</table>
<?php $form->end(); ?>
</div>
</td></tr>
<tr><td colspan="2" height="10px"></td></tr>
</table>

<?php
$result = $this->get_array('adResult');
?>

<table class="data_table" cellpadding="0" cellspacing="0" style="width: 99%;margin: 0px auto;">
<tr class="row_heading_tr">
<td width="180px"><?php echo $this->get_label('site name');?></td>
<?php
if(isset($result['heading']))
{
?>
	<td width="80px"><?php echo $this->get_label('type');?></td>
	<?php
	foreach($result['heading'] as $hkey => $hvalue)
	{

		?>
		<td><bdi><?php echo $this->get_label($hvalue);?></bdi></td>
		<?php
	}
}
?>
</tr>
<?php

if(count($result) == 0)
{
?>
<tr><td colspan="12" style="padding-left: 5px;" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php
} else {
	foreach($result as $key => $value)
	{
		if($key == "heading")
		continue;

		$rowspan = count($value['reportData'])-2;//heading,total
		$iii     = 0;

		foreach($value['reportData'] as $key1 => $value1)
		{
			if($key1 == "heading" || $key1 == "total")
			continue;
			?>
			<tr class="row_data_tr">
			<td <?php if($iii == 0){?> rowspan="<?php echo $rowspan;?>"<?php }else{?> style="display:none;" <?php } ?>>
			<a target="_parent" href="<?php echo $this->make_base_url("report/adcodes_publisher",ADMIN_DIR);?>"><?php echo $value['url'];?></a>

			&nbsp;
			<a target="_parent" href="<?php echo $this->make_base_url("dispatch/category_targeting/22/".$value['id'],ADMIN_DIR);?>"><i class="fa fa-bar-chart report-icon" title="<?php echo $this->get_label('details');?>"></i></a>
			</td>
			<td class="border-left"><?php echo $this->get_label($key1);?></td>

			<?php
			foreach($value1 as $key2=>$value2)
			{

			?>
			<td ><bdi><?php echo $value2;?></bdi></td>
			<?php
			}

			$iii++;
		}
		?>
		</tr>
		<?php
	}
	?>
	<tr><td colspan="15" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
	<?php
}
?>
</table>
</body>
</html>
