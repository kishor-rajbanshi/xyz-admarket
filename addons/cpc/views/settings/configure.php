<?php 
$validate=array(
		"click_value"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"budget"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"min_cpc_daily_budget"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"profit_percentage"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		)
);

$form=$this->create_form();
$form->start("cpcsettings","","post",$validate);

	$profit_percentage=$this->get_variable("profit_percentage");
	$click_value=$this->get_variable("click_value");
	$budget=$this->get_variable("budget");
	$ppc_impression_tracking_interval=$this->get_variable("ppc_impression_tracking_interval");
	$min_cpc_daily=$this->get_variable("min_cpc_daily_budget");
?>
     
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="width: 240px;height: 40px;"><?php echo $this->get_label('publisher profit percentage');?>  ( % )</td>
<td><input type="text" name="profit_percentage" id="profit_percentage" value="<?php echo $profit_percentage;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"/><span class="compulsory">*</span></td>
</tr> 


<tr>
<td style="width: 240px;height: 40px;"><?php echo $this->get_label('min click value');?> ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="click_value" id="click_value" value="<?php echo $click_value;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span></td>
</tr>

<tr>
<td style="width: 240px;height: 40px;"><?php echo $this->get_label('min budget');?> ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="budget" id="budget" value="<?php echo $budget;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span></td>
</tr>

<tr>
<td style="width: 240px;height: 40px;"><?php echo $this->get_label('min cpc daily budget');?> ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="min_cpc_daily_budget" id="min_cpc_daily_budget" value="<?php echo $min_cpc_daily;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span></td>
</tr>


<tr>
<td><?php echo $this->get_label('ppc impression tracking interval');?></td>
<td>
<select name="ppc_impression_tracking_interval" id="ppc_impression_tracking_interval" style="width: 100px;">
<?php for($i=1;$i <=60;$i++){?>
<option value="<?php echo $i;?>"  <?php if($ppc_impression_tracking_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select>
&nbsp;
<?php echo $this->get_label('sec');?>

</td>
</tr>

<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div> 
</div>
<?php $form->end(); ?>