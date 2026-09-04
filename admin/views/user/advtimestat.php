<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-1.7.min.js'></script>
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
$from_date=$this->get_variable('from_date');
$to_date=$this->get_variable('to_date');

if($from_date !='' && $to_date =='')
$to_date=date("d",time()).'/'.date("m",time()).'/'.date("Y",time());

$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");
?>

</head>
<body style="background: none;">

<table class="iframe_table" cellpadding="0" cellspacing="0" border="0" >

<tr><td colspan="7" height="10px;"></td></tr>




<tr><td colspan="7">
 <div class="search_div">
<table class="search_div_table">
<?php

$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');

if($from_date =='' && $duration ==7)
$duration=1;

$form1=$this->create_form();
$form1->start("timestatistics",$this->make_url("user/advtimestat/").$uid,"post");
?>

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
</td>

    <td>
        &nbsp;
    <input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="5" />

<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="5" />
    &nbsp;

    </td>
    <td>

    </td>
    <td>&nbsp;&nbsp; <input type="submit" name="search" class="link_button" value="<?php echo $this->get_label('go');?>" /></td>



  </tr>

 <?php $form1->end(); ?>

</table>
</div>
</td></tr>

<tr><td colspan="7" height="10px"></td></tr>

<tr><td colspan="7">
<table style="width: 99%;margin: 4px;" class="data_table" cellpadding="0" cellspacing="0">

<?php
$iindex = 0;

foreach($reportResultTimeperiod as $rkey => $rvalue)
{
    if($iindex == 0)
    {?>
		<tr class="data_table_head">

		<?php
		foreach ($rvalue as $rkey1 => $rvalue1)
		{?>
			<td><bdi><?php echo $rvalue1;?></bdi></td>
		<?php
		}
		?>

		</tr>
	<?php
	}
	else
	{
		$enterflag = 0;
		$rowSpan   = count($rvalue);

		foreach($rvalue as $rkey1 => $rvalue1)
		{?>

			<tr class="data_table_content">
			<td <?php if($enterflag == 0){?> rowspan="<?php echo $rowSpan;?>" <?php } else {?> style="display: none;"<?php } ?>><bdi><?php echo $rkey;?></bdi></td>

			<td class="border-left"><bdi><?php echo $this->get_label($rkey1);?></bdi></td>

			<?php foreach ($rvalue1 as $rkey11 => $rvalue11){?>
			<td><bdi><?php echo $rvalue11;?></bdi></td>
			<?php }?>

			</tr>

			<?php
			$enterflag++;
		}
	}

	$iindex++;
}
?>

</table>
</td></tr>
</table>
</body>
</html>
