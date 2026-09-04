<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:20px;padding-top:20px;"><u><b><?php echo $this->get_label('targeted isp');?></b></u></div>
	<?php
	$numbers=$this->get_variable('numbers');

	if($numbers==0) {?>
	<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_message('all isp targeted');?></div>
	<?php }
	
	else{	$res=$this->get_result('res'); ?>
		<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><div class="col-md-6 col-sm-6 col-xs-6"><u>ISP Name</u></div><div class="col-md-6 col-sm-6 col-xs-6"><u>Country</u></div></div>
	<?php 
		foreach($res as $key1=>$value1)
		{ 
			$row=$this->get_isp_name($value1['ispid']);
		
			?>
	<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><div class="col-md-6 col-sm-6 col-xs-6"><?php echo $row[0];?></div><div class="col-md-6 col-sm-6 col-xs-6"><?php echo $this->get_country_name($row[1]);?></div></div>
	<?php }
	} ?>