<?php 
 $this->dispatch("layout/header/5/1/p");
 $from_date = $this->get_variable('from_date');
 $to_date   = $this->get_variable('to_date');
 $duration  = $this->get_variable('duration');

 if($from_date == '' && $duration == 7)
 $duration = 1;
 if($from_date != '' && $to_date == '')
 $to_date = date("d",time()).'/'.date("m",time()).'/'.date("Y",time());
 ?>
 <script type="text/javascript">
 $(document).ready(function() 
 {
 	 $("#from_date").datepicker({dateFormat : 'dd/mm/yy'});
 	 $("#to_date").datepicker({dateFormat : 'dd/mm/yy'});
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
 		$('.custom-date-div').show();
 		if(window.parent.$('.custom-date-div').length > 0)
 		window.parent.$('.custom-date-div').show();
 	}
 	else
 	{
 		$('.custom-date-div').hide();
 		if(window.parent.$('.custom-date-div').length > 0)
 		window.parent.$('.custom-date-div').hide();
 		$('#from_date').val('');
 		$('#to_date').val('');
 	}
}
 </script>
<h2 class="dashbord-heading"><?php echo $this->get_label('publisher dashboard');?>
<?php
			$form2 = $this->create_form();
			$form2->start("overall_adreport",$this->make_url("dashboard/publisher_home"),"post");
			?>
				<span class="duration-filter">
					<span class="me-2">
						<select class="form-select" name="duration" id="duration">
							<option value="1" <?php if($duration==1) { echo "selected"; } ?>><?php echo $this->get_label('today');?></option>
							<option value="6" <?php if($duration==6) { echo "selected"; } ?>><?php echo $this->get_label('yesterday');?></option>
							<option value="2" <?php if($duration==2) { echo "selected"; } ?>><?php echo $this->get_label('last 14');?></option>
							<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
							<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
							<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
							<option value="7" <?php if($duration==7) { echo "selected"; } ?>><?php echo $this->get_label('custom date');?></option>
						</select>
					</span>
					<span class="custom-date-div">
						<input class="form-control" type="text" readonly name="from_date" id="from_date" value="<?php echo $from_date;?>" placeholder="<?php echo $this->get_label('from date');?>" />
						<input class="form-control" type="text" readonly name="to_date" id="to_date" value="<?php echo $to_date;?>" placeholder="<?php echo $this->get_label('to date');?>" />
					</span>
					<span>
						<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
					</span>
				</span>
			<?php $form2->end(); ?>
</h2>
<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
		<iframe id="iframe-section-8" frameborder="0" style="width: 100%;" src="<?php echo $this->make_url("dashboard/publisher_report/".$duration."/".$this->mybase64_encode($from_date)."/".$this->mybase64_encode($to_date));?>" allowtransparency="true"></iframe>
	</div>
</div>

<?php
if(Configuration::get_instance()->read('countrywise_data_tracking') ==1)
{

	?>

	<div class="row">
		<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
			<iframe id="iframe-section-4" frameborder="0" style="width: 100%;" src="<?php echo $this->make_base_url("dashboard/publisher_country/".$duration."/".$this->mybase64_encode($from_date)."/".$this->mybase64_encode($to_date));?>" allowtransparency="true"></iframe>
		</div>
	</div>
<?php }?>

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
		<iframe id="iframe-section-2" frameborder="0" style="width: 100%;" src="<?php echo $this->make_url("dashboard/adcode_report/".$duration."/".$this->mybase64_encode($from_date)."/".$this->mybase64_encode($to_date)); ?>" allowtransparency="true"></iframe>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 ">
		<iframe id="iframe-section-6" frameborder="0" style="width: 100%;" src="<?php echo $this->make_url("dashboard/top_adcodes/".$duration);?>" allowtransparency="true"></iframe>
	</div>
</div>
<?php $this->dispatch("layout/footer");?>