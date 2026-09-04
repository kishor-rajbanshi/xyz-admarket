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
$uid=$this->get_variable('uid');
if($uid==0)
$this->dispatch("layout/header/5/_54");
else
$this->dispatch("layout/header/5/_55");

$aduid 		= $this->get_variable('aduid');
$duration 	= $this->get_variable('duration');
$name 		= $this->get_variable('name');
$tab 		= $this->get_variable('tab');
$username 	= $this->get_variable('username');
$adpricing 	= $this->get_variable('adpricing');
$adcodeType = $this->get_variable("adcodeType");
?>
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

<div class="sub_menu_main"><?php echo $this->get_label('detailed statistics of adunit',array('x'=>'','y'=>$name))." ";?></div>

<?php
if($uid==0)
$this->dispatch("links/links/21");
else
$this->dispatch("links/links/29");
?>
<table style="width: 100%;"  cellpadding="0" cellspacing="0">
<tr><td colspan="6" style="height: 30px;"><?php echo $this->get_label('created by')." ";?><?php if($uid >0){?><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $username;?></a><?php }else{ echo $username;}?></td></tr>
<tr><td colspan="6" >

<div class="search_div">
<?php
$form1=$this->create_form();
$form1->start("adunitallstatistics",$this->make_url("report/adcodes_detailed/".$aduid),"post");
?>

<table class="search_div_table">
<?php
if($from_date =='' && $duration ==7)
$duration=1;
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
&nbsp;&nbsp;
<input type="text" readonly="readonly" name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="From Date" size="8" />
&nbsp;
<input type="text" readonly="readonly" name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="To Date" size="8" />
&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;
<input type="hidden" name="tab" id="tab" value="1"/>
<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
</tr>
<tr><td colspan="4" style="height: 10px;"></td></tr>
</table>
<?php $form1->end(); ?>

</div>
</td></tr>

<tr><td colspan="4" style="height: 10px;"></td></tr>


<tr><td colspan="4">
<table style="width: 100%;" >

<tr class="statistics_header">
<td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('overall');?></td>
<td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('time based reports');?></td>
<td></td>
</tr>

<tr id="showstat1" class="showadustatclass statistics_tr">
<td colspan="6" class="statistics_td"   style="padding: 5px;">

<table style="width: 100%;" cellpadding="0" cellspacing="0" class="data_table">

<?php
$reportResult      = $this->get_array("reportResult");
$reportResultCount = count($reportResult);

foreach($reportResult as $rkey => $rvalue)
{
	if($adcodeType == 17 && $rkey == 'cpm')
	continue;

	if($reportResultCount <= 3 && $rkey == 'total')
    continue;

    if($rkey == 'heading')
    {?>
      <tr class="row_heading_tr">
      <td style="width: 100px;"></td>
    <?php
    }
    else
    { ?>
      <tr class="row_data_tr">
      <td ><?php echo $this->get_label($rkey);?></td>
    <?php
    }

	foreach ($rvalue as $rkey1 => $rvalue1){?>
	<td <?php if($rkey == 'heading'){?>style="width: 150px;"<?php } ?>><bdi><?php echo $rvalue1;?></bdi></td>
	<?php }?>
	</tr>
	<?php
}
?>
</table>

</td>
</tr>


<tr id="showstat2" class="showadustatclass statistics_tr">
<td colspan="6" class="statistics_td"  style="padding: 5px;">

<table style="width: 100%;" cellpadding="0" cellspacing="0"  class="data_table">

<?php
$reportResultTimeperiod = $this->get_array("reportResultTimeperiod");

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
		if($adcodeType == 17 && isset($rvalue['cpm']))
		unset($rvalue['cpm']);

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

</td>
</tr>
</table>

</td></tr>
</table>
<?php $this->dispatch("layout/footer");?>
<script type="text/javascript">
show_pub_stat(<?php echo $tab;?>);
</script>
