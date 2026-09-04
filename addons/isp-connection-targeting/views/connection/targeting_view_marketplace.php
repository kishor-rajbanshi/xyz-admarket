<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:20px;padding-top:20px;"><u><b><?php echo $this->get_label('targeted connection');?></b></u></div>
	<?php
	$numbers=$this->get_variable('numbers');

	if($numbers==0) {?>
	<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_message('all connection targeted');?></div>
	<?php }
	
	else{	$res=$this->get_result('res');
		
		foreach($res as $key1=>$value1)
		{?>
	<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo ucfirst($this->get_connection_name($value1['conn_id']));?></div> 	
	<?php }
	} ?>

