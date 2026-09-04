<?php 
$this->dispatch("layout/header/17");

$uid=$this->get_variable('uid');
$frompg=$this->get_variable('frompg');


$status=$this->get_variable('status');
$type=$this->get_variable('type');
$pg=$this->get_variable("pg");
$search_by=$this->get_variable("search_by");


$res=$this->get_result('res');
$value1=$res[0];
$adv_status=$value1['adv_status'];
$pub_status=$value1['pub_status'];


$form=$this->create_form();
$form->start("change_status",$this->make_url("user/change_status/").$uid."/".$frompg."/".$status."/".$type."/".$search_by."/".$pg,"post");
?>

<div class="sub_menu_main"><?php echo $this->get_label('change user status');?></div>

<?php $this->dispatch("links/links/5/".$uid);?>

<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td style="width: 150px;"><?php echo $this->get_label('username');?></td>
<td>:</td>
<td><a href="<?php echo $this->make_url("user/profile/".$uid."/0");?>"><?php echo $this->get_variable("usname");?></a></td>
</tr>	

<tr>
<td ><?php echo $this->get_label('advertiser status');?></td>
<td >:</td>
<td><?php echo $this->get_user_status($adv_status);	?></td>
</tr>	

<tr>
<td><?php echo $this->get_label('publisher status');?></td>
<td>:</td>
<td><?php echo $this->get_user_status($pub_status);	?></td>
</tr>
			
<tr>
<td><?php echo $this->get_label('new advertiser status');?></td>
<td>:</td>
<td>
<?php 
if($adv_status==ADV_PENDING)
{
?>
<select name="statusadv">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('approve');?></option>
<option value="0"><?php echo $this->get_label('reject');?></option>
</select>
<?php 
}
else if($adv_status==ADV_ACTIVE)
{
?>
<select name="statusadv">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="0"><?php echo $this->get_label('block');?></option>
</select>
<?php 
}
else if($adv_status==ADV_BLOCKED)
{
?>
<select name="statusadv">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('activate');?></option>
</select>
<?php 
}
else if($adv_status==ADV_NO_EMAIL_VERIFY)
{
?>
<select name="statusadv">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('approve');?></option>
<option value="0"><?php echo $this->get_label('reject');?></option>
</select>
<?php 
}
else if($adv_status==ADV_NO_ACCOUNT)
{
?>
<input type="hidden" name="statusadv" id="statusadv" value="-4"/>
<?php echo $this->get_label('na');?>
<?php 
}
?>

</td>
</tr>

<tr>
<td><?php echo $this->get_label('new publisher status');?></td>
<td>:</td>
<td>

<?php 
if($pub_status==PUB_PENDING)
{
?>
<select name="statuspub">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('approve');?></option>
<option value="0"><?php echo $this->get_label('reject');?></option>
</select>
<?php 
}
else if($pub_status==PUB_ACTIVE)
{
?>
<select name="statuspub">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="0"><?php echo $this->get_label('block');?></option>
</select>
<?php 
}
else if($pub_status==PUB_BLOCKED)
{
?>
<select name="statuspub">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('activate');?></option>
</select>
<?php 
}
else if($pub_status==PUB_NO_EMAIL_VERIFY)
{
?>
<select name="statuspub">
<option value="-4"><?php echo $this->get_label('select');?></option>
<option value="1"><?php echo $this->get_label('approve');?></option>
<option value="0"><?php echo $this->get_label('reject');?></option>
</select>
<?php 
}
else if($pub_status==PUB_NO_ACCOUNT)
{
?>
<input type="hidden" name="statuspub" id="statuspub" value="-4"/>
<?php echo $this->get_label('na');?>
<?php 
}
?>

</td>
</tr>
<tr><td colspan="2"></td><td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('change status1');?>"></td></tr>
</table>
</div>
</div>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>