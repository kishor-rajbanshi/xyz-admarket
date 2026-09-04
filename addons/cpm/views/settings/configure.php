<?php 
$validate=array(
		"default_cpm_rate"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"cpm_profit_percentage"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		)
);

$form=$this->create_form();
$form->start("cpmsettings","","post",$validate);


	$default_cpm_rate=$this->get_variable("default_cpm_rate");
	$cpm_profit_percentage=$this->get_variable("cpm_profit_percentage");
	$cpm_ad_impression_limit_hour=$this->get_variable("cpm_ad_impression_limit_hour");
	$cpm_interval=$this->get_variable("cpm_interval");
	$cpm_impression_tracking_interval=$this->get_variable("cpm_impression_tracking_interval");
	$min_cpm_budget=$this->get_variable("min_cpm_budget");
	$min_cpm_daily=$this->get_variable("min_cpm_daily");
?>
     
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>

<tr>
<td height="30px"><?php echo $this->get_label('cpm default rate');?>  ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="default_cpm_rate" id="default_cpm_rate" value="<?php echo $default_cpm_rate;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span></td>
</tr>


      
<tr>
<td width="240px" height="30px"><?php echo $this->get_label('cpm profit percentage');?> ( % )</td>
<td><input type="text" name="cpm_profit_percentage" id="cpm_profit_percentage" value="<?php echo $cpm_profit_percentage;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>

</td>
</tr>

<tr>
<td width="240px" height="30px"><?php echo $this->get_label('min cpm total budget');?></td>
<td><input type="text" name="min_cpm_total_budget" id="min_cpm_total_budget" value="<?php echo $min_cpm_budget;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>

</td>
</tr>


<tr>
<td width="240px" height="30px"><?php echo $this->get_label('min cpm daily budget');?></td>
<td><input type="text" name="min_cpm_daily_budget" id="min_cpm_daily_budget" value="<?php echo $min_cpm_daily;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>

</td>
</tr>



<tr>
<td width="220px" height="30px"><?php echo $this->get_label('cpm impression limit/ip/ad/hour');?></td>
<td>
<input type="text" name="cpm_ad_impression_limit_hour" id="cpm_ad_impression_limit_hour" value="<?php echo $cpm_ad_impression_limit_hour;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">
&nbsp;
<?php echo $this->get_label('impressions');?> / 

<select name="cpm_interval" id="cpm_interval" >
<?php for($i=1;$i <=24;$i++){?>
<option value="<?php echo $i;?>"  <?php if($cpm_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select>
&nbsp;
<?php echo $this->get_label('hrs');?>
</td>
</tr>


<tr>
<td width="240px" height="30px"><?php echo $this->get_label('cpm impression tracking interval');?></td>
<td>
<select name="cpm_impression_tracking_interval" id="cpm_impression_tracking_interval" >
<?php for($i=1;$i <=60;$i++){?>
<option value="<?php echo $i;?>"  <?php if($cpm_impression_tracking_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
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