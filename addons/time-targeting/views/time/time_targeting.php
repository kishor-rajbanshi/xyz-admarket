<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH?>js/jquery-ui-1.8.23.custom.min.js'></script>
<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
	$active_theme=Configuration::get_instance()->read('active_theme');
?>


<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />


<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>


</head>
<body>

<?php
$message=$this->get_variable("message");
$aid=$this->get_variable('aid');
?>




<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 5px;">
<div class="checkbox_head"><?php echo $this->get_label('time targeting');?></div>




<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;margin-bottom: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 10px;margin-bottom: 0px;">

<?php if($message !=''){?>
<div class="col-md-12 col-sm-12 col-xs-12 padding-side" id="main_alert" style="color: red;"><?php echo $message;?></div>
<?php }?>



<?php
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


<div class="notification"><?php echo $this->get_label('choose current time message',array('x'=>date('d/m/Y H:i:s a',time())));?></div>




<?php if(Configuration::get_instance()->read('date_filter_enabled') ==1){?>

<div class="form-group">

<div class="checkbox_head" style="margin-bottom: 10px;"><?php echo $this->get_label('date filtering');?></div>



<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">
<input type="radio" name="datefilter" id="datefilter0" onclick="LoadDatePeriod();" value="0" checked="checked" />
<?php echo $this->get_label('no date restriction');?></div>


<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">


<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl">
<input type="radio" name="datefilter" id="datefilter1" onclick="LoadDatePeriod();" value="1" <?php if($datefilter ==1){?>checked="checked"<?php }?>  />
<?php echo $this->get_label('start date');?></div> 

<div class="col-md-2 col-sm-2 col-xs-12 padding-side">
<input type="text" name="startdate" id="startdate" readonly="readonly" style="width: 100px;" value="<?php echo $startdate;?>" />
</div> 

<div class="col-md-1 col-sm-2 col-xs-12" style="padding-right: 0px;">
<?php echo $this->get_label('end date');?></div> 

<div class="col-md-2 col-sm-2 col-xs-12 padding-side">
<input type="text" name="enddate" id="enddate" readonly="readonly" style="width: 100px;" value="<?php echo $enddate;?>" />
</div>


</div>




<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">


<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl" >
<input type="radio" name="datefilter" id="datefilter2" onclick="LoadDatePeriod();" value="2" <?php if($datefilter ==2){?>checked="checked"<?php }?>  />

<?php echo $this->get_label('choose a period');?>
</div>



<div class="col-md-10 col-sm-9 col-xs-12 padding-side" >
<span class="col-md-2 col-sm-2 col-xs-12 padding-side targeting-span"><input type="radio" name="date_period" id="date_period1" value="1" checked="checked" /><?php echo $this->get_label('1 weeks');?></span>
<span class="col-md-2 col-sm-2 col-xs-12 padding-side targeting-span"><input type="radio" name="date_period" id="date_period2" value="2" <?php if($date_period ==2){?>checked="checked"<?php }?> /><?php echo $this->get_label('2 weeks');?></span>
<span class="col-md-2 col-sm-2 col-xs-12 padding-side targeting-span"><input type="radio" name="date_period" id="date_period3" value="3" <?php if($date_period ==3){?>checked="checked"<?php }?> /><?php echo $this->get_label('3 weeks');?></span>
<span class="col-md-2 col-sm-2 col-xs-12 padding-side targeting-span"><input type="radio" name="date_period" id="date_period4" value="4" <?php if($date_period ==4){?>checked="checked"<?php }?> /><?php echo $this->get_label('4 weeks');?></span>

</div>
</div>

<div class="notification"><?php echo $this->get_label('choose period message');?></div>



</div>


<?php }?>



<?php if(Configuration::get_instance()->read('time_filter_enabled') ==1){?>

<div class="form-group">

<div class="checkbox_head" style="margin-bottom: 10px;margin-top: 10px;"><?php echo $this->get_label('time filtering');?></div>




<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side"><input type="radio" name="timefilter" id="timefilter0" value="0" checked="checked" onclick="LoadTimePeriod();" /><?php echo $this->get_label('no hourly restriction');?></div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">

<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl">
<input type="radio" name="timefilter" id="timefilter1" value="1" onclick="LoadTimePeriod();" <?php if($timefilter ==1){?>checked="checked"<?php }?>  />



<?php echo $this->get_label('from time');?></div>

<div class="col-md-2 col-sm-3 col-xs-12 padding-side">
<select name="starttime" id="starttime" class="form-control" style="max-width: 140px;">
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


<div class="col-md-1 col-sm-2 col-xs-12" style="padding-right: 0px;"><?php echo $this->get_label('to time');?></div>


<div class="col-md-2 col-sm-3 col-xs-12 padding-side">
<select name="endtime" id="endtime" class="form-control" style="max-width: 140px;">
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

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">



<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl" >

<input type="radio" name="timefilter" id="timefilter2" value="2" onclick="LoadTimePeriod();" <?php if($timefilter ==2){?>checked="checked"<?php }?>  />



<?php echo $this->get_label('choose the hours');?>
</div> 

<div class="col-md-10 col-sm-9 col-xs-12 padding-side" >
<span class="col-md-3 col-sm-6 col-xs-12 padding-side targeting-span1"><input type="checkbox" name="time_period1" id="time_period1" value="1" <?php if($time_period1 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('morning 6am-9am');?></span>
<span class="col-md-3 col-sm-6 col-xs-12 padding-side targeting-span1"><input type="checkbox" name="time_period2" id="time_period2" value="1" <?php if($time_period2 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('pre noon 9am-12noon');?></span>
<span class="col-md-4 col-sm-6 col-xs-12 padding-side targeting-span1"><input type="checkbox" name="time_period3" id="time_period3" value="1" <?php if($time_period3 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('after noon 12noon-4pm');?></span>
<span class="col-md-3 col-sm-6 col-xs-12 padding-side targeting-span1"><input type="checkbox" name="time_period4" id="time_period4" value="1" <?php if($time_period4 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('evening 4pm-8pm');?></span>
<span class="col-md-4 col-sm-6 col-xs-12 padding-side targeting-span1"><input type="checkbox" name="time_period5" id="time_period5" value="1" <?php if($time_period5 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('night hours 8pm-6am');?></span>
</div>



</div>

</div>


<?php }?>




<?php if(Configuration::get_instance()->read('day_filter_enabled') ==1){?>

<div class="form-group">

<div class="checkbox_head1" style="margin-bottom: 10px;margin-top: 10px;"><?php echo $this->get_label('day filtering');?></div>




<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side"><input type="radio" name="dayfilter" id="dayfilter0" value="0" checked="checked" onclick="LoadDayPeriod();" /><?php echo $this->get_label('no day restriction');?></div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">

<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl">
<input type="radio" name="dayfilter" id="dayfilter1" value="1" onclick="LoadDayPeriod();" <?php if($dayfilter ==1){?>checked="checked"<?php }?>  />

<?php echo $this->get_label('from day');?>
</div>

<div class="col-md-2 col-sm-3 col-xs-12 padding-side">

<select name="startday" id="startday" class="form-control" style="max-width: 140px;">
<option value="7" <?php if($startday ==7){?>selected<?php }?> ><?php echo $this->get_label('sunday');?></option>
<option value="1" <?php if($startday ==1){?>selected<?php }?> ><?php echo $this->get_label('monday');?></option>
<option value="2" <?php if($startday ==2){?>selected<?php }?> ><?php echo $this->get_label('tuesday');?></option>
<option value="3" <?php if($startday ==3){?>selected<?php }?> ><?php echo $this->get_label('wednesday');?></option>
<option value="4" <?php if($startday ==4){?>selected<?php }?> ><?php echo $this->get_label('thursday');?></option>
<option value="5" <?php if($startday ==5){?>selected<?php }?> ><?php echo $this->get_label('friday');?></option>
<option value="6" <?php if($startday ==6){?>selected<?php }?> ><?php echo $this->get_label('saturday');?></option>
</select>

</div>

<div class="col-md-1 col-sm-2 col-xs-12" style="padding-right: 0px;"><?php echo $this->get_label('to day');?></div>

<div class="col-md-2 col-sm-3 col-xs-12 padding-side">
<select name="endday" id="endday" class="form-control" style="max-width: 140px;">
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

<div class="form-group col-md-12 col-sm-12 col-xs-12 padding-side">


<div class="col-md-2 col-sm-3 col-xs-12 padding-side padding-side-rtl" >
<input type="radio" name="dayfilter" id="dayfilter2" value="2" onclick="LoadDayPeriod();" <?php if($dayfilter ==2){?>checked="checked"<?php }?>  />

<?php echo $this->get_label('choose specific days');?>
</div>


<div class="col-md-10 col-sm-9 col-xs-12 padding-side" >
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period7" id="day_period7" value="1" <?php if($day_period7 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('sunday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period1" id="day_period1" value="1" <?php if($day_period1 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('monday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period2" id="day_period2" value="1" <?php if($day_period2 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('tuesday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period3" id="day_period3" value="1" <?php if($day_period3 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('wednesday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period4" id="day_period4" value="1" <?php if($day_period4 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('thursday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period5" id="day_period5" value="1" <?php if($day_period5 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('friday');?></span>
<span class="col-md-3 col-sm-3 col-xs-12 padding-side targeting-span2"><input type="checkbox" name="day_period6" id="day_period6" value="1" <?php if($day_period6 ==1){?>checked="checked"<?php }?> /><?php echo $this->get_label('saturday');?></span>
</div>


</div>


</div>

<?php }?>



<div class="form-group col-md-12 col-sm-12 col-xs-12">
<input type="hidden" name="aid" value="<?php echo $aid;?>" />
<input class="btn btn-primary btn-lg" type="submit" name="keysubmit" value="<?php echo $this->get_label('update');?>" />
</div>

<?php $form->end(); ?>


</div>
</div>
</div>


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
	$('.targeting-span').show();
	else
	$('.targeting-span').hide();
	
}


function LoadTimePeriod()
{
	type=$('input[name=timefilter]:checked').val();
	if(type ==2)
	$('.targeting-span1').show();
	else
	$('.targeting-span1').hide();
}


function LoadDayPeriod()
{
	type=$('input[name=dayfilter]:checked').val();
	if(type ==2)
	$('.targeting-span2').show();
	else
	$('.targeting-span2').hide();
}




</script>
</body>
</html>