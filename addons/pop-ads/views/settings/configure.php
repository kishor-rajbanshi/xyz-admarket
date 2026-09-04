<?php 
$validate=array(
		"default_pop_rate"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		),
		"pop_profit_percentage"=>array(
				"notNull"=>array($this->get_message("not null")),
				"isPositive"=>array($this->get_message("positive value"))
		)
);

$form=$this->create_form();
$form->start("popsettings","","post",$validate);


	$default_pop_rate=$this->get_variable("default_pop_rate");
	$pop_profit_percentage=$this->get_variable("pop_profit_percentage");
	$pop_ad_impression_limit_hour=$this->get_variable("pop_ad_impression_limit_hour");
	$pop_interval=$this->get_variable("pop_interval");
	$pop_impression_tracking_interval=$this->get_variable("pop_impression_tracking_interval");
	$pop_ads_support=$this->get_variable("pop_ads_support");
	$pop_window_width=$this->get_variable("pop_window_width");
	$pop_window_height=$this->get_variable("pop_window_height");
	$pop_display_count_day=$this->get_variable("pop_display_count_day");	
	$pop_direct_link_enabled=$this->get_variable("pop_direct_link_enabled");		
	$min_pop_budget=$this->get_variable("min_pop_budget");
	$min_pop_daily_budget=$this->get_variable("min_pop_daily_budget");
	$pop_addon_usage=$this->get_variable("pop_addon_usage");

	
	$poparray=explode('-',$pop_ads_support);
	
	
	
	?>
<div class="inner-box">
<div class="pages-input">


<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td></td><td ><?php echo $this->get_label('compulsory message');?></td></tr>


<tr>
<td style="height: 30px;width: 260px;"><?php echo $this->get_label('pop addon usage');?></td>
<td>
<select name="pop_addon_usage" id="pop_addon_usage" style="width: 85px;" onchange="LoadPopSection();">
	<option value="1" <?php if($pop_addon_usage ==1) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('adtype');?></option>
	<option value="0" <?php if($pop_addon_usage ==0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('pricing');?></option>
</select>

<div class="notification pop-section-notification"><?php echo $this->get_label('cpm or cpa addon should be required');?></div>

</td>
</tr>



<tr class="pop-section" style="display: none;">
<td style="height: 30px;width: 260px;"><?php echo $this->get_label('pop default rate');?>  ( <?php echo Configuration::get_instance()->read('currency_symbol');?> )</td>
<td><input type="text" name="default_pop_rate" id="default_pop_rate" value="<?php echo $default_pop_rate;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span></td>
</tr>


      
<tr class="pop-section" style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('pop profit percentage');?> ( % )</td>
<td><input type="text" name="pop_profit_percentage" id="pop_profit_percentage" value="<?php echo $pop_profit_percentage;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>
</td>
</tr>

<tr class="pop-section" style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('min pop total budget');?></td>
<td><input type="text" name="min_pop_budget" id="min_pop_budget" value="<?php echo $min_pop_budget;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>
</td>
</tr>

<tr class="pop-section" style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('min pop daily budget');?></td>
<td><input type="text" name="min_pop_daily_budget" id="min_pop_daily_budget" value="<?php echo $min_pop_daily_budget;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9\.]/g, '');"><span class="compulsory">*</span>
</td>
</tr>


<tr class="pop-section" style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('pop impression limit/ip/ad/hour');?></td>
<td>
<input type="text" name="pop_ad_impression_limit_hour" id="pop_ad_impression_limit_hour" value="<?php echo $pop_ad_impression_limit_hour;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');">
&nbsp;
<?php echo $this->get_label('impressions');?> / 

<select name="pop_interval" id="pop_interval" >
<?php for($i=1;$i <=24;$i++){?>
<option value="<?php echo $i;?>"  <?php if($pop_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select>
&nbsp;
<?php echo $this->get_label('hrs');?>
</td>
</tr>


<tr class="pop-section" style="display: none;">
<td style="height: 30px;"><?php echo $this->get_label('pop impression tracking interval');?></td>
<td>
<select name="pop_impression_tracking_interval" id="pop_impression_tracking_interval" style="width: 85px;">
<?php for($i=1;$i <=60;$i++){?>
<option value="<?php echo $i;?>"  <?php if($pop_impression_tracking_interval ==$i){ ?>selected="selected"<?php } ?>><?php echo $i;?></option>
<?php }?>
</select>
&nbsp;
<?php echo $this->get_label('sec');?>

</td>
</tr>




<tr >
<td style="height: 30px;"><?php echo $this->get_label('pop display count day');?></td>
<td>
<input type="text" name="pop_display_count_day" id="pop_display_count_day" value="<?php echo $pop_display_count_day;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');" />
&nbsp;
<?php echo $this->get_label('min');?>
</td>
</tr>







<tr>
<td style="height: 30px;"><?php echo $this->get_label('supported pop type');?></td>
<td>


<input type="checkbox" name="popup" id="popup" <?php if($poparray[0] ==1){?> checked="checked" <?php }?> value="1" /> <?php echo $this->get_label('popup');?>

<input type="checkbox" name="popunder" id="popunder" <?php if($poparray[1] ==1){?> checked="checked" <?php }?> value="1" /> <?php echo $this->get_label('popunder');?>

<input type="checkbox" name="poptab" id="poptab" <?php if($poparray[2] ==1){?> checked="checked" <?php }?> value="1" /> <?php echo $this->get_label('poptab');?>


<span class="compulsory">*</span>

</td>
</tr>



<tr>
<td style="height: 30px;"><?php echo $this->get_label('pop direct link enabled');?></td>
<td>
<select name="pop_direct_link_enabled" id="pop_direct_link_enabled" style="width: 85px;">
	<option value="1"  <?php if($pop_direct_link_enabled ==1)  { ?>selected="selected"<?php } ?>><?php echo $this->get_label('yes');?></option>
	<option value="0" <?php if($pop_direct_link_enabled ==0) { ?>selected="selected"<?php } ?>><?php echo $this->get_label('no');?></option>
</select>
</td>
</tr>



<tr>
<td style="height: 30px;"><?php echo $this->get_label('pop window width');?></td>
<td><input type="text" name="pop_window_width" id="pop_window_width" value="<?php echo $pop_window_width;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');"><span class="compulsory">*</span>

</td>
</tr>

<tr>
<td style="height: 30px;"><?php echo $this->get_label('pop window height');?></td>
<td><input type="text" name="pop_window_height" id="pop_window_height" value="<?php echo $pop_window_height;?>" size="5" onkeyup="this.value=this.value.replace(/[^0-9]/g, '');"><span class="compulsory">*</span>

</td>
</tr>


<tr><td></td><td align="left"><input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" ></td></tr>
</table>
</div> 
</div>
<?php $form->end(); ?>
<script type="text/javascript">
function LoadPopSection()
{
	if($('#pop_addon_usage').val() ==1)
	{
		$('.pop-section').hide();
		$('.pop-section-notification').show();
	}
	else
	{
		$('.pop-section').show();	
		$('.pop-section-notification').hide();
	}
}

LoadPopSection();
</script>