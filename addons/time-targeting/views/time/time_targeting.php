<?php $this->dispatch("layout/header_iframe", PATH_TO_ROOT);?>

<?php
$message=$this->get_variable("message");
$aid=$this->get_variable('aid');

$datefilter=$this->get_variable('datefilter');
$startdate=$this->get_variable('startdate');
$enddate=$this->get_variable('enddate');

$date_period=$this->get_variable('date_period');

$timefilter=$this->get_variable('timefilter');
$starttime=$this->get_variable('starttime');
$endtime=$this->get_variable('endtime');

$time_period1=$this->get_variable('time_period1');
$time_period2=$this->get_variable('time_period2');
$time_period3=$this->get_variable('time_period3');
$time_period4=$this->get_variable('time_period4');
$time_period5=$this->get_variable('time_period5');



$dayfilter=$this->get_variable('dayfilter');
$startday=$this->get_variable('startday');
$endday=$this->get_variable('endday');
$day_period1=$this->get_variable('day_period1');
$day_period2=$this->get_variable('day_period2');
$day_period3=$this->get_variable('day_period3');
$day_period4=$this->get_variable('day_period4');
$day_period5=$this->get_variable('day_period5');
$day_period6=$this->get_variable('day_period6');
$day_period7=$this->get_variable('day_period7');


$form=$this->create_form();
$form->start("timetargeting","","post");
?>
<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 time-targeting">

<h2 class="section-heading"><?php echo $this->get_label('time targeting');?></h2>

<?php if($message != ""){?>
<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message"><?php echo $message;?></div>
<?php }?>

<div class="notification"><?php echo $this->get_label('choose current time message',array('x'=>date('d/m/Y H:i:s a',time())));?></div>

<?php if(Configuration::get_instance()->read('date_filter_enabled') ==1){?>

<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="section-sub-heading"><?php echo $this->get_label('date filtering');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12">
	<div class="col-md-12 col-sm-12 col-xs-12 form-check">
		<input class="form-check-input mt-1" type="radio" name="datefilter" id="datefilter0" onClick="LoadDatePeriod();" value="0" checked="checked" />
		<label class="form-check-label"><?php echo $this->get_label('no date restriction');?></label>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12 form-check">
		<input class="form-check-input mt-1" type="radio" name="datefilter" id="datefilter1" onClick="LoadDatePeriod();" value="1" <?php if($datefilter ==1){?>checked="checked"<?php }?> />
		<label class="form-check-label"><?php echo $this->get_label('choose date range');?></label>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12 form-check">
		<input class="form-check-input mt-1" type="radio" name="datefilter" id="datefilter2" onClick="LoadDatePeriod();" value="2" <?php if($datefilter ==2){?>checked="checked"<?php }?> />
		<label class="form-check-label"><?php echo $this->get_label('choose a period');?></label>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12 date-range-span">
		<div class="row">
			<div class="col-md-6 col-sm-6 col-xs-12">
				<div class="time-target-box">
					<label class="form-label mb-1"><?php echo $this->get_label('start date');?></label>
					<input type="text" class="form-control" name="startdate" id="startdate" readonly="readonly" value="<?php echo $startdate;?>" />
				</div>

				<div class="time-target-box">
					<label class="form-label mb-1"><?php echo $this->get_label('end date');?></label>
					<input type="text" class="form-control" name="enddate" id="enddate" readonly="readonly" value="<?php echo $enddate;?>" />
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-12 col-sm-12 col-xs-12 targeting-span">
		<div class="row m-0">
			<div class="col-md-2 col-sm-2 col-xs-6 form-check">
				<input type="radio" class="form-check-input mt-1" name="date_period" id="date_period1" value="1" checked="checked" />
				<label class="form-check-label"><?php echo $this->get_label('1 weeks');?></label>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 form-check">
				<input type="radio" class="form-check-input mt-1" name="date_period" id="date_period2" value="2" <?php if($date_period ==2){?>checked="checked"<?php }?> />
				<label class="form-check-label"><?php echo $this->get_label('2 weeks');?></label>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 form-check">
				<input type="radio" class="form-check-input mt-1" name="date_period" id="date_period3" value="3" <?php if($date_period ==3){?>checked="checked"<?php }?> />
				<label class="form-check-label"><?php echo $this->get_label('3 weeks');?></label>
			</div>
			<div class="col-md-2 col-sm-2 col-xs-6 form-check">
				<input type="radio" class="form-check-input mt-1" name="date_period" id="date_period4" value="4" <?php if($date_period ==4){?>checked="checked"<?php }?> />
				<label class="form-check-label"><?php echo $this->get_label('4 weeks');?></label>
			</div>
		</div>
	</div>
</div>

<div class="notification"><?php echo $this->get_label('choose period message');?></div>
</div>
<?php }?>



<?php if(Configuration::get_instance()->read('time_filter_enabled') ==1){?>

<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="section-sub-heading"><?php echo $this->get_label('time filtering');?></h2>

	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="col-md-12 col-sm-12 col-xs-12 form-check">
			<input type="radio" class="form-check-input mt-1" name="timefilter" id="timefilter0" value="0" checked="checked" onClick="LoadTimePeriod();" />
			<label class="form-check-label"><?php echo $this->get_label('no hourly restriction');?></label>
		</div>

		<div class="col-md-12 col-sm-12 col-xs-12 form-check">
			<input type="radio" class="form-check-input mt-1" name="timefilter" id="timefilter1" value="1" onClick="LoadTimePeriod();" <?php if($timefilter ==1){?>checked="checked"<?php }?>  />
			<label class="form-check-label"><?php echo $this->get_label('choose time range');?></label>
		</div>

		<div class="col-md-12 col-sm-12 col-xs-12 form-check">
			<input type="radio" class="form-check-input mt-1" name="timefilter" id="timefilter2" value="2" onClick="LoadTimePeriod();" <?php if($timefilter ==2){?>checked="checked"<?php }?>  />
			<label class="form-check-label"><?php echo $this->get_label('choose the hours');?></label>
		</div>

		<div class="col-md-12 col-sm-12 col-xs-12 time-range-span">
			<div class="row">
				<div class="col-md-6 col-sm-6 col-xs-12">
					<div class="time-target-box">
						<label class="form-label mb-1"><?php echo $this->get_label('from time');?></label>
						<select name="starttime" id="starttime" class="form-select" style="max-width: 140px;">
							<option value="0" <?php if($starttime ==0){?>selected<?php }?> >12:00 <?php echo $this->get_label('mid night');?></option>
							<option value="1" <?php if($starttime ==1){?>selected<?php }?> >1:00 <?php echo $this->get_label('am');?></option>
							<option value="2" <?php if($starttime ==2){?>selected<?php }?> >2:00 <?php echo $this->get_label('am');?></option>
							<option value="3" <?php if($starttime ==3){?>selected<?php }?> >3:00 <?php echo $this->get_label('am');?></option>
							<option value="4" <?php if($starttime ==4){?>selected<?php }?> >4:00 <?php echo $this->get_label('am');?></option>
							<option value="5" <?php if($starttime ==5){?>selected<?php }?> >5:00 <?php echo $this->get_label('am');?></option>
							<option value="6" <?php if($starttime ==6){?>selected<?php }?> >6:00 <?php echo $this->get_label('am');?></option>
							<option value="7" <?php if($starttime ==7){?>selected<?php }?> >7:00 <?php echo $this->get_label('am');?></option>
							<option value="8" <?php if($starttime ==8){?>selected<?php }?> >8:00 <?php echo $this->get_label('am');?></option>
							<option value="9" <?php if($starttime ==9){?>selected<?php }?> >9:00 <?php echo $this->get_label('am');?></option>
							<option value="10" <?php if($starttime ==10){?>selected<?php }?> >10:00 <?php echo $this->get_label('am');?></option>
							<option value="11" <?php if($starttime ==11){?>selected<?php }?> >11:00 <?php echo $this->get_label('am');?></option>
							<option value="12" <?php if($starttime ==12){?>selected<?php }?> >12:00 <?php echo $this->get_label('noon');?></option>
							<option value="13" <?php if($starttime ==13){?>selected<?php }?> >1:00 <?php echo $this->get_label('pm');?></option>
							<option value="14" <?php if($starttime ==14){?>selected<?php }?> >2:00 <?php echo $this->get_label('pm');?></option>
							<option value="15" <?php if($starttime ==15){?>selected<?php }?> >3:00 <?php echo $this->get_label('pm');?></option>
							<option value="16" <?php if($starttime ==16){?>selected<?php }?> >4:00 <?php echo $this->get_label('pm');?></option>
							<option value="17" <?php if($starttime ==17){?>selected<?php }?> >5:00 <?php echo $this->get_label('pm');?></option>
							<option value="18" <?php if($starttime ==18){?>selected<?php }?> >6:00 <?php echo $this->get_label('pm');?></option>
							<option value="19" <?php if($starttime ==19){?>selected<?php }?> >7:00 <?php echo $this->get_label('pm');?></option>
							<option value="20" <?php if($starttime ==20){?>selected<?php }?> >8:00 <?php echo $this->get_label('pm');?></option>
							<option value="21" <?php if($starttime ==21){?>selected<?php }?> >9:00 <?php echo $this->get_label('pm');?></option>
							<option value="22" <?php if($starttime ==22){?>selected<?php }?> >10:00 <?php echo $this->get_label('pm');?></option>
							<option value="23" <?php if($starttime ==23){?>selected<?php }?> >11:00 <?php echo $this->get_label('pm');?></option>
						</select>
					</div>


					<div class="time-target-box">
						<label class="form-label mb-1"><?php echo $this->get_label('to time');?></label>
						<select name="endtime" id="endtime" class="form-select" style="max-width: 140px;">
							<option value="0" <?php if($endtime ==0){?>selected<?php }?> >1:00 <?php echo $this->get_label('am');?></option>
							<option value="1" <?php if($endtime ==1){?>selected<?php }?> >2:00 <?php echo $this->get_label('am');?></option>
							<option value="2" <?php if($endtime ==2){?>selected<?php }?> >3:00 <?php echo $this->get_label('am');?></option>
							<option value="3" <?php if($endtime ==3){?>selected<?php }?> >4:00 <?php echo $this->get_label('am');?></option>
							<option value="4" <?php if($endtime ==4){?>selected<?php }?> >5:00 <?php echo $this->get_label('am');?></option>
							<option value="5" <?php if($endtime ==5){?>selected<?php }?> >6:00 <?php echo $this->get_label('am');?></option>
							<option value="6" <?php if($endtime ==6){?>selected<?php }?> >7:00 <?php echo $this->get_label('am');?></option>
							<option value="7" <?php if($endtime ==7){?>selected<?php }?> >8:00 <?php echo $this->get_label('am');?></option>
							<option value="8" <?php if($endtime ==8){?>selected<?php }?> >9:00 <?php echo $this->get_label('am');?></option>
							<option value="9" <?php if($endtime ==9){?>selected<?php }?> >10:00 <?php echo $this->get_label('am');?></option>
							<option value="10" <?php if($endtime ==10){?>selected<?php }?> >11:00 <?php echo $this->get_label('am');?></option>
							<option value="11" <?php if($endtime ==11){?>selected<?php }?> >12:00 <?php echo $this->get_label('noon');?></option>
							<option value="12" <?php if($endtime ==12){?>selected<?php }?> >1:00 <?php echo $this->get_label('pm');?></option>
							<option value="13" <?php if($endtime ==13){?>selected<?php }?> >2:00 <?php echo $this->get_label('pm');?></option>
							<option value="14" <?php if($endtime ==14){?>selected<?php }?> >3:00 <?php echo $this->get_label('pm');?></option>
							<option value="15" <?php if($endtime ==15){?>selected<?php }?> >4:00 <?php echo $this->get_label('pm');?></option>
							<option value="16" <?php if($endtime ==16){?>selected<?php }?> >5:00 <?php echo $this->get_label('pm');?></option>
							<option value="17" <?php if($endtime ==17){?>selected<?php }?> >6:00 <?php echo $this->get_label('pm');?></option>
							<option value="18" <?php if($endtime ==18){?>selected<?php }?> >7:00 <?php echo $this->get_label('pm');?></option>
							<option value="19" <?php if($endtime ==19){?>selected<?php }?> >8:00 <?php echo $this->get_label('pm');?></option>
							<option value="20" <?php if($endtime ==20){?>selected<?php }?> >9:00 <?php echo $this->get_label('pm');?></option>
							<option value="21" <?php if($endtime ==21){?>selected<?php }?> >10:00 <?php echo $this->get_label('pm');?></option>
							<option value="22" <?php if($endtime ==22){?>selected<?php }?> >11:00 <?php echo $this->get_label('pm');?></option>
							<option value="23" <?php if($endtime ==23){?>selected<?php }?> >12:00 <?php echo $this->get_label('mid night');?></option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-12 col-sm-12 col-xs-12 targeting-span1">
			<div class="row m-0">
				<div class="col-md-3 col-sm-4 col-xs-6 form-check">
					<input type="checkbox" class="form-check-input mt-1" name="time_period1" id="time_period1" value="1" <?php if($time_period1 ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('morning 6am-9am');?></label>
				</div>
				<div class="col-md-3 col-sm-4 col-xs-6 form-check">
					<input type="checkbox" class="form-check-input mt-1" name="time_period2" id="time_period2" value="1" <?php if($time_period2 ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('pre noon 9am-12noon');?></label>
				</div>
				<div class="col-md-3 col-sm-4 col-xs-6 form-check">
					<input type="checkbox" class="form-check-input mt-1" name="time_period3" id="time_period3" value="1" <?php if($time_period3 ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('after noon 12noon-4pm');?></label>
				</div>
				<div class="col-md-3 col-sm-4 col-xs-6 form-check">
					<input type="checkbox" class="form-check-input mt-1" name="time_period4" id="time_period4" value="1" <?php if($time_period4 ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('evening 4pm-8pm');?></label>
				</div>
				<div class="col-md-3 col-sm-4 col-xs-6 form-check">
					<input type="checkbox" class="form-check-input mt-1" name="time_period5" id="time_period5" value="1" <?php if($time_period5 ==1){?>checked="checked"<?php }?> />
					<label class="form-check-label"><?php echo $this->get_label('night hours 8pm-6am');?></label>
				</div>
			 </div>
		</div>

	</div>
</div>
<?php }?>

<?php if(Configuration::get_instance()->read('day_filter_enabled') ==1){?>
<div class="col-md-12 col-sm-12 col-xs-12">
<h2 class="section-sub-heading"><?php echo $this->get_label('day filtering');?></h2>

    <div class="col-md-12 col-sm-12 col-xs-12">

      <div class="col-md-12 col-sm-12 col-xs-12 form-check">
      	<input type="radio" name="dayfilter" class="form-check-input mt-1" id="dayfilter0" value="0" checked="checked" onClick="LoadDayPeriod();" />
				<label class="form-check-label"><?php echo $this->get_label('no day restriction');?></label>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12 form-check">
      	<input type="radio" name="dayfilter" class="form-check-input mt-1" id="dayfilter1" value="1" onClick="LoadDayPeriod();" <?php if($dayfilter ==1){?>checked="checked"<?php }?>  />
				<label class="form-check-label"><?php echo $this->get_label('choose day range');?></label>
      </div>

      <div class="col-md-12 col-sm-12 col-xs-12 form-check">
      	<input type="radio" name="dayfilter" class="form-check-input mt-1" id="dayfilter2" value="2" onClick="LoadDayPeriod();" <?php if($dayfilter ==2){?>checked="checked"<?php }?>  />
				<label class="form-check-label"><?php echo $this->get_label('choose specific days');?></label>
      </div>

	    <div class="col-md-12 col-sm-12 col-xs-12 day-range-span">
				<div class="row">
					<div class="col-md-6 col-sm-6 col-xs-12">
		        <div class="time-target-box">
		            <label class="form-label mb-1"><?php echo $this->get_label('from day');?></label>
		            <select name="startday" id="startday" class="form-select" style="max-width: 140px;">
			            <option value="7" <?php if($startday ==7){?>selected<?php }?> ><?php echo $this->get_label('sunday');?></option>
			            <option value="1" <?php if($startday ==1){?>selected<?php }?> ><?php echo $this->get_label('monday');?></option>
			            <option value="2" <?php if($startday ==2){?>selected<?php }?> ><?php echo $this->get_label('tuesday');?></option>
			            <option value="3" <?php if($startday ==3){?>selected<?php }?> ><?php echo $this->get_label('wednesday');?></option>
			            <option value="4" <?php if($startday ==4){?>selected<?php }?> ><?php echo $this->get_label('thursday');?></option>
			            <option value="5" <?php if($startday ==5){?>selected<?php }?> ><?php echo $this->get_label('friday');?></option>
			            <option value="6" <?php if($startday ==6){?>selected<?php }?> ><?php echo $this->get_label('saturday');?></option>
		            </select>
		        </div>

		        <div class="time-target-box">
		            <label class="form-label mb-1"><?php echo $this->get_label('to day');?></label>
		            <select name="endday" id="endday" class="form-select" style="max-width: 140px;">
			            <option value="1" <?php if($endday ==1){?>selected<?php }?> ><?php echo $this->get_label('monday');?></option>
			            <option value="2" <?php if($endday ==2){?>selected<?php }?> ><?php echo $this->get_label('tuesday');?></option>
			            <option value="3" <?php if($endday ==3){?>selected<?php }?> ><?php echo $this->get_label('wednesday');?></option>
			            <option value="4" <?php if($endday ==4){?>selected<?php }?> ><?php echo $this->get_label('thursday');?></option>
			            <option value="5" <?php if($endday ==5){?>selected<?php }?> ><?php echo $this->get_label('friday');?></option>
			            <option value="6" <?php if($endday ==6){?>selected<?php }?> ><?php echo $this->get_label('saturday');?></option>
			            <option value="7" <?php if($endday ==7){?>selected<?php }?> ><?php echo $this->get_label('sunday');?></option>
		            </select>
		        </div>
					</div>
				</div>
	    </div>

	    <div class="col-md-12 col-sm-12 col-xs-12 targeting-span2">
				<div class="row m-0">
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period7" id="day_period7" value="1" <?php if($day_period7 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('sunday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period1" id="day_period1" value="1" <?php if($day_period1 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('monday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period2" id="day_period2" value="1" <?php if($day_period2 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('tuesday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period3" id="day_period3" value="1" <?php if($day_period3 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('wednesday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period4" id="day_period4" value="1" <?php if($day_period4 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('thursday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period5" id="day_period5" value="1" <?php if($day_period5 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('friday');?></label>
					</div>
	        <div class="col-md-3 col-sm-3 col-xs-6 form-check">
						<input type="checkbox" class="form-check-input mt-1" name="day_period6" id="day_period6" value="1" <?php if($day_period6 ==1){?>checked="checked"<?php }?> />
						<label class="form-check-label"><?php echo $this->get_label('saturday');?></label>
					</div>
				</div>
	    </div>
		</div>
</div>

<?php }?>

	<div class="col-md-12 col-sm-12 col-xs-12">
		<input type="hidden" name="aid" value="<?php echo $aid;?>" />
		<input class="submit-button" type="submit" name="keysubmit" value="<?php echo $this->get_label('update');?>" />
	</div>

</div>

<?php $form->end(); ?>

<script type="text/javascript">
$(document).ready(function() {
	$("#startdate").datepicker({dateFormat : 'dd/mm/yy'});
	$("#enddate").datepicker({dateFormat : 'dd/mm/yy'});




	<?php if(Configuration::get_instance()->read('date_filter_enabled') ==1){?>
	LoadDatePeriod();
	<?php }?>


	<?php if(Configuration::get_instance()->read('time_filter_enabled') ==1){?>
	LoadTimePeriod();
	<?php }?>

	<?php if(Configuration::get_instance()->read('day_filter_enabled') ==1){?>
	LoadDayPeriod();
	<?php }?>
});

function LoadDatePeriod()
{
	type=$('input[name=datefilter]:checked').val();
	if(type ==2)
	{
		$('.targeting-span').show();
		$('.date-range-span').hide();
	}
	else if(type ==1)
	{
		$('.targeting-span').hide();
		$('.date-range-span').show();
	}
	else
	{
		$('.targeting-span').hide();
		$('.date-range-span').hide();
	}
}

function LoadTimePeriod()
{
	type=$('input[name=timefilter]:checked').val();
	if(type ==2)
	{
		$('.targeting-span1').show();
		$('.time-range-span').hide();
	}
	else if(type ==1)
	{
		$('.targeting-span1').hide();
		$('.time-range-span').show();
	}
	else
	{
		$('.targeting-span1').hide();
		$('.time-range-span').hide();
	}
}


function LoadDayPeriod()
{
	type=$('input[name=dayfilter]:checked').val();

	if(type ==2)
	{
		$('.targeting-span2').show();
		$('.day-range-span').hide();
	}
	else if(type ==1)
	{
		$('.targeting-span2').hide();
		$('.day-range-span').show();
	}
	else
	{
		$('.targeting-span2').hide();
		$('.day-range-span').hide();
	}
}
</script>
<?php $this->dispatch("layout/footer_iframe", PATH_TO_ROOT);?>
