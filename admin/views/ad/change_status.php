<?php
$this->dispatch("layout/header/1/_11");

$aid=$this->get_variable('aid');
$frompg=$this->get_variable('frompg');
$status=$this->get_variable('status');
$uid=$this->get_variable('uid');
$ecommerce_parent=$this->get_variable('ecommerce_parent');
$adtype=$this->get_variable('adtype');


$duration=$this->get_variable('duration');
$at=$this->get_variable('at');
$st=$this->get_variable('st');
$pg=$this->get_variable('pg');
$adv=$this->get_variable('adv');
$adpricing=$this->get_variable('adpricing');
$actmapping=$this->get_variable('actmapping');
$search_by=$this->get_variable('search_by');
$premiumStatus=$this->get_variable('premiumStatus');

$form=$this->create_form();
$form->start("change_status",$this->make_url("ad/change_status/").$aid."/".$frompg."/".$duration."/".$at."/".$st."/".$adv."/".$adpricing."/".$search_by."/".$pg,"post");



?>

<div class="sub_menu_main"><?php echo $this->get_label('manage ad pricing',array('x'=>$this->get_ad_pricing($aid)));?></div><?php $this->dispatch("links/links/9");?>
<input type="hidden" name="search_by" value="<?php echo $search_by;?>">
<input type="hidden" name="adpricing" value="<?php echo $adpricing;?>">
<input type="hidden" name="premiumStatus" value="<?php echo $premiumStatus;?>">

<table style="width: 100%;">


<?php if($adtype !=7 || ($adtype ==7 && $ecommerce_parent >0)){?>
<tr><td colspan="3"><?php $this->dispatch("ad/preview/".$aid."/4");?></td></tr>
<?php }?>

<tr><td colspan="3" height="10px"></td></tr>


<tr>
<td width="100px"><?php echo $this->get_label('operation');?></td>
<td>:</td>
<td>
<?php if($status == 1){?>
<select name="status">
<option value="0"><?php echo $this->get_label('block ad');?></option>
<?php if($actmapping ==0){?>
<option value="2"><?php echo $this->get_label('delete ad');?></option>
<?php }?>
</select>
<?php }else if($status == -1){?>
<select name="status">
<option value="1"><?php echo $this->get_label('activate ad');?></option>
<option value="0"><?php echo $this->get_label('block ad');?></option>
<?php if($actmapping ==0){?>
<option value="2"><?php echo $this->get_label('delete ad');?></option>
<?php }?>
</select>
<?php } else if($status == 0){?>
<select name="status">
<option value="1"><?php echo $this->get_label('activate ad');?></option>
<?php if($actmapping ==0){?>
<option value="2"><?php echo $this->get_label('delete ad');?></option>
<?php }?>
</select>
<?php } ?>

</td>

</tr>
<tr><td colspan="3" height="10px"></td></tr>
<tr><td></td><td></td><td  align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('change status1');?>"></td></tr>

<tr><td colspan="3" height="20px"></td></tr>
</table>
<?php $form->end(); ?>
<?php $this->dispatch("layout/footer");?>
