<?php $this->dispatch("layout/header/3/_38");?>
<script type="text/javascript">
$(document).ready(function() {
	$("#time").datepicker({dateFormat : 'dd/mm/yy'});
});
</script>
<?php

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);


$validate=array(
		"message"=>array(
				"notNull"=>array($this->get_message("not null"))
		),
		"time"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

?>

<script type="text/javascript">
function show_tab(tab)
{
	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');

	$('#showtab'+tab).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);
}
</script>
<?php

$form=$this->create_form();
$form->start("createnotification","","post",$validate);



$id=$this->get_variable('id');
$type=$this->get_variable('type');
$time=$this->get_variable('time');
$message=$this->get_variable('message');
$dbtype=$this->get_variable('dbtype');
$status=$this->get_variable('status');
$pg=$this->get_variable('pg');

?>

<div class="sub_menu_main"><?php echo $this->get_label('edit notification');?></div>

<?php $this->dispatch("links/links/60");?>


<div class="inner-box">
<div class="pages-input">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td ></td><td><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width:150px;"><?php echo $this->get_label('notifications for');?></td>
<td>
<select name="type" id="type" style="width: 130px;">
<option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('advertisers');?></option>
<option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('publishers');?></option>
<option value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('adv & pub');?></option>
<option value="3" <?php if($type==3) echo "selected"; ?>><?php echo $this->get_label('visitors');?></option>
</select>
</td>
</tr>

<tr>
<td><?php echo $this->get_label('notification expiry');?></td>
<td>
<input type="text" name="time" id="time" readonly="readonly" style="width: 120px;background-color:#EEEEEE;" value="<?php echo $time;?>" /><span class="compulsory">*</span>
</td>
</tr>

<tr><td colspan="2" style="height: 30px;"></td></tr>



<tr>
<td colspan="2">

<table style="width: 100%;" class="inner-table" >
<tr class="statistics_header">

<td onclick="show_tab(0);"  id="tb0" class="tabclass" style="width: 120px;"><?php echo $this->get_label('default content');?></td>

<?php
if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	 <td onclick="show_tab(<?php echo $value1['id'];?>);"  id="tb<?php echo $value1['id'];?>" class="tabclass" style="width: 100px;"><?php echo $value1['description']; ?></td>
<?php }}?>

<td class="tabclass"></td>
</tr>


	<tr id="showtab0" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">

	<table style="width: 100%" cellpadding="0" cellspacing="0">

	<tr>
	<td style="width: 160px;"><?php echo $this->get_label('notification message');?></td>
	<td><textarea name="message" id="message" rows="5" cols="50"><?php echo $message;?></textarea><span class="compulsory">*</span></td>
	</tr>

	<tr><td colspan="2" style="height: 30px;"></td></tr>

	</table>
	</td>
	</tr>


<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">

	<table style="width: 100%" cellpadding="0" cellspacing="0">

	  <tr>
	  <td style="width: 160px;"><?php echo $this->get_label('notification message'); ?></td>
	  <td>
  	  <textarea name="<?php echo $value1['id'];?>_message" id="<?php echo $value1['id'];?>_message" rows="5" cols="50"><?php echo $this->get_variable($value1['id'].'_message');?></textarea>
	  </td>
	  </tr>

	  <tr><td colspan="2" style="height: 30px;"></td></tr>


	</table>
	</td>
	</tr>
<?php }}?>


</table>
</td></tr>


<tr><td></td>
<td  align="left">
<input type="hidden" name="dbtype" id="dbtype" value="<?php echo $dbtype;?>" />
<input type="hidden" name="status" id="status" value="<?php echo $status;?>" />
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="hidden" name="pg" id="pg" value="<?php echo $pg;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('submit');?>"></td>
</tr>

</table>
</div>
</div>
<?php $form->end(); ?>

<script type="text/javascript">
show_tab(0);
</script>

<?php $this->dispatch("layout/footer");?>
