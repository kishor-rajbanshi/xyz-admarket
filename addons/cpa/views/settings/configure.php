<?php 
$validate=array(
		"default_cpa_rate"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"cpa_profit_percentage"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"cpa_conversion_tracking_interval"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer"))
		),
		"min_cpa_total_budget"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isInteger"=>array($this->get_message("positive integer")),
				"isGreaterThan"=>array('default_cpa_rate',1,$this->get_message("min cpa total budget greater than def cpa rate"))
				
		)
);


$form=$this->create_form();
$form->start("cpasettings","","post",$validate);

	$default_cpa_rate=$this->get_variable("default_cpa_rate");
	$min_cpa_total_budget=$this->get_variable("min_cpa_total_budget");
	$cpa_profit_percentage=$this->get_variable("cpa_profit_percentage");
	$cpa_conversion_tracking_interval=$this->get_variable("cpa_conversion_tracking_interval");
	$cpa_impression_tracking_interval=$this->get_variable("cpa_impression_tracking_interval");
	$daily_conversion_data_backup_expiry=$this->get_variable("daily_conversion_data_backup_expiry");
	
?>
     
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td style="height: 40px;"><?php echo $this->get_label('cpa default rate');?>  ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="default_cpa_rate" id="default_cpa_rate" value="<?php echo $default_cpa_rate;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" /><span class="compulsory">*</span></td>
</tr>

<tr>
<td style="height: 40px;"><?php echo $this->get_label('min cpa total budget');?>  ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="min_cpa_total_budget"" id="min_cpa_total_budget" value="<?php echo $min_cpa_total_budget;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" /></td>
</tr>
      
<tr>
<td style="height: 40px;width: 240px;"><?php echo $this->get_label('cpa profit percentage');?> ( % )</td>
<td><input type="text" name="cpa_profit_percentage" id="cpa_profit_percentage" value="<?php echo $cpa_profit_percentage;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');" /><span class="compulsory">*</span></td>
</tr>


<tr>
<td style="height: 40px;"><?php echo $this->get_label('cpa conversion tracking interval');?></td>
<td>
<input type="text" name="cpa_conversion_tracking_interval" id="cpa_conversion_tracking_interval" value="<?php echo $cpa_conversion_tracking_interval;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
&nbsp;
<?php echo $this->get_label('days');?>
</td>
</tr>


<tr>
<td style="height: 40px;width: 240px;"><?php echo $this->get_label('cpa impression tracking interval');?></td>
<td>
<select name="cpa_impression_tracking_interval" id="cpa_impression_tracking_interval" style="width: 85px;">
<?php for($i=1;$i <=60;$i++){?>
<option value="<?php echo $i;?>"  <?php if($cpa_impression_tracking_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select> <?php echo $this->get_label('sec');?>

</td>
</tr>



<tr>
<td style="height: 40px;width: 240px;"><?php echo $this->get_label('daily conversion data backup expiry');?></td>
<td>
<select name="daily_conversion_data_backup_expiry" id="daily_conversion_data_backup_expiry" style="width: 85px;">
<?php for($i=1;$i <=12;$i++){?>
<option value="<?php echo $i;?>"  <?php if($daily_conversion_data_backup_expiry ==$i)  { ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select> <?php echo $this->get_label('months');?>
</td>
</tr>  

<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div> 
</div>
<?php $form->end(); ?>