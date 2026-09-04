<table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0">

	<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	
	<tr class="no_border"><td height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('time targeting of',array('x'=>$this->get_variable('adname')));?></td></tr>
	
	<?php 
	$dbcount=$this->get_variable('dbcount');	
	
	if($dbcount ==0){?>
	<tr class="no_border"><td  height="40px"><?php echo $this->get_label('no restrictions');?></td></tr>
	<?php }else if($dbcount >0){
		
		$dbadstatus=$this->get_variable('dbadstatus');
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
		
		
		
		
		
		
		
		
		
		
		
		?>
<?php if(Configuration::get_instance()->read('date_filter_enabled') ==1){?>
<tr class="no_border"><td style="font-size: 14px;text-decoration: underline;"><?php echo $this->get_label('date filtering');?></td></tr>

<tr class="no_border"><td><div class="notification"><?php echo $this->get_label('choose current time message',array('x'=>date_default_timezone_get(),'y'=>date('d/m/Y H:i:s a',time())));?></div></td></tr>

<?php if(($datefilter ==1 || $datefilter ==2) && $dbadstatus ==-1) {?>
<tr class="no_border"><td  height="40px"><div style="height: 20px;" class="notification"><?php echo $this->get_label('choose period message');?></div></td></tr>
<?php }?>

<?php if($datefilter ==0){?>
<tr class="no_border"><td  height="40px"><?php echo $this->get_label('no date restriction');?></td></tr>
<?php }else if($datefilter ==1){?>

<tr class="no_border"><td  height="40px"><?php echo $this->get_label('start date');?> - <?php echo $startdate;?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('end date');?> - <?php echo $enddate;?></td></tr>

<?php }else if($datefilter ==2){?>

<tr class="no_border"><td  height="40px"><?php echo $date_period;?>&nbsp;<?php echo $this->get_label('week');?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('start date');?> - <?php echo $startdate;?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('end date');?> - <?php echo $enddate;?></td></tr>

<?php }?>






<?php }?>
	
	
	
<?php if(Configuration::get_instance()->read('time_filter_enabled') ==1){?>
<tr class="no_border"><td style="font-size: 14px;text-decoration: underline;"><?php echo $this->get_label('time filtering');?></td></tr>

<?php if($timefilter ==0){?>
<tr class="no_border"><td  height="40px"><?php echo $this->get_label('no hourly restriction');?></td></tr>
<?php }else if($timefilter ==1){?>
<tr class="no_border"><td  height="40px"><?php echo $this->get_label('from time');?> - <?php 

if($starttime ==0)
echo '12:00 '.$this->get_label('mid night');
else if($starttime ==1)
echo '1:00 '.$this->get_label('am');
else if($starttime ==2)
echo '2:00 '.$this->get_label('am');
else if($starttime ==3)
echo '3:00 '.$this->get_label('am');
else if($starttime ==4)
echo '4:00 '.$this->get_label('am');
else if($starttime ==5)
echo '5:00 '.$this->get_label('am');
else if($starttime ==6)
echo '6:00 '.$this->get_label('am');
else if($starttime ==7)
echo '7:00 '.$this->get_label('am');
else if($starttime ==8)
echo '8:00 '.$this->get_label('am');
else if($starttime ==9)
echo '9:00 '.$this->get_label('am');
else if($starttime ==10)
echo '10:00 '.$this->get_label('am');
else if($starttime ==11)
echo '11:00 '.$this->get_label('am');
else if($starttime ==12)
echo '12:00 '.$this->get_label('noon');
else if($starttime ==13)
echo '1:00 '.$this->get_label('pm');
else if($starttime ==14)
echo '2:00 '.$this->get_label('pm');
else if($starttime ==15)
echo '3:00 '.$this->get_label('pm');
else if($starttime ==16)
echo '4:00 '.$this->get_label('pm');
else if($starttime ==17)
echo '5:00 '.$this->get_label('pm');
else if($starttime ==18)
echo '6:00 '.$this->get_label('pm');
else if($starttime ==19)
echo '7:00 '.$this->get_label('pm');
else if($starttime ==20)
echo '8:00 '.$this->get_label('pm');
else if($starttime ==21)
echo '9:00 '.$this->get_label('pm');
else if($starttime ==22)
echo '10:00 '.$this->get_label('pm');
else if($starttime ==23)
echo '11:00 '.$this->get_label('pm');

?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('to time');?> - <?php 



if($endtime ==0)
echo '1:00 '.$this->get_label('am');
else if($endtime ==1)
echo '2:00 '.$this->get_label('am');
else if($endtime ==2)
echo '3:00 '.$this->get_label('am');
else if($endtime ==3)
echo '4:00 '.$this->get_label('am');
else if($endtime ==4)
echo '5:00 '.$this->get_label('am');
else if($endtime ==5)
echo '6:00 '.$this->get_label('am');
else if($endtime ==6)
echo '7:00 '.$this->get_label('am');
else if($endtime ==7)
echo '8:00 '.$this->get_label('am');
else if($endtime ==8)
echo '9:00 '.$this->get_label('am');
else if($endtime ==9)
echo '10:00 '.$this->get_label('am');
else if($endtime ==10)
echo '11:00 '.$this->get_label('am');
else if($endtime ==11)
echo '12:00 '.$this->get_label('noon');
else if($endtime ==12)
echo '1:00 '.$this->get_label('pm');
else if($endtime ==13)
echo '2:00 '.$this->get_label('pm');
else if($endtime ==14)
echo '3:00 '.$this->get_label('pm');
else if($endtime ==15)
echo '4:00 '.$this->get_label('pm');
else if($endtime ==16)
echo '5:00 '.$this->get_label('pm');
else if($endtime ==17)
echo '6:00 '.$this->get_label('pm');
else if($endtime ==18)
echo '7:00 '.$this->get_label('pm');
else if($endtime ==19)
echo '8:00 '.$this->get_label('pm');
else if($endtime ==20)
echo '9:00 '.$this->get_label('pm');
else if($endtime ==21)
echo '10:00 '.$this->get_label('pm');
else if($endtime ==22)
echo '11:00 '.$this->get_label('pm');
else if($endtime ==23)
echo '12:00 '.$this->get_label('mid night');
?></td></tr>
<?php }else if($timefilter ==2){?>

<?php if($time_period1 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('morning 6am-9am');?></td></tr><?php }?>
<?php if($time_period2 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('pre noon 9am-12noon');?></td></tr><?php }?>
<?php if($time_period3 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('after noon 12noon-4pm');?></td></tr><?php }?>
<?php if($time_period4 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('evening 4pm-8pm');?></td></tr><?php }?>
<?php if($time_period5 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('night hours 8pm-6am');?></td></tr><?php }?>

<?php }?>
<?php }?>	
	
	
	
<?php if(Configuration::get_instance()->read('day_filter_enabled') ==1){?>
<tr class="no_border"><td style="font-size: 14px;text-decoration: underline;"><?php echo $this->get_label('day filtering');?></td></tr>

<?php if($dayfilter ==0){?>
<tr class="no_border"><td  height="40px"><?php echo $this->get_label('no day restriction');?></td></tr>
<?php }else if($dayfilter ==1){?>
<tr class="no_border"><td  height="40px"><?php echo $this->get_label('from day');?> - <?php 
if($startday ==1)
echo $this->get_label('monday');
else if($startday ==2)
echo $this->get_label('tuesday');
else if($startday ==3)
echo $this->get_label('wednesday');
else if($startday ==4)
echo $this->get_label('thursday');
else if($startday ==5)
echo $this->get_label('friday');
else if($startday ==6)
echo $this->get_label('saturday');
else if($startday ==7)
echo $this->get_label('sunday');
?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $this->get_label('to day');?> - <?php 
if($endday ==1)
echo $this->get_label('monday');
else if($endday ==2)
echo $this->get_label('tuesday');
else if($endday ==3)
echo $this->get_label('wednesday');
else if($endday ==4)
echo $this->get_label('thursday');
else if($endday ==5)
echo $this->get_label('friday');
else if($endday ==6)
echo $this->get_label('saturday');
else if($endday ==7)
echo $this->get_label('sunday');
?></td></tr>
<?php }else if($dayfilter ==2){?>

<?php if($day_period7 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('sunday');?></td></tr><?php }?>
<?php if($day_period1 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('monday');?></td></tr><?php }?>
<?php if($day_period2 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('tuesday');?></td></tr><?php }?>
<?php if($day_period3 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('wednesday');?></td></tr><?php }?>
<?php if($day_period4 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('thursday');?></td></tr><?php }?>
<?php if($day_period5 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('friday');?></td></tr><?php }?>
<?php if($day_period6 ==1){?><tr class="no_border"><td  height="30px"><?php echo $this->get_label('saturday');?></td></tr><?php }?>

<?php }?>
<?php }?>		
		
	
<?php }?>
</table>