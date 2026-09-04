<table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0">

<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	
<tr class="no_border"><td height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted isp of',array('x'=>$this->get_variable('adname')));?></td></tr>
	<?php
	$numbers=$this->get_variable('numbers');

	if($numbers==0) {?>
	<tr class="no_border"><td  height="40px"><?php echo $this->get_message('all isp targeted');?></td></tr>
	<?php }
	
	else{	$res=$this->get_result('res');
		echo '<tr class="no_border"><td><strong>ISP Name</strong></td><td><strong>Country</strong></td></tr>';
		foreach($res as $key1=>$value1)
		{ 
			$row=$this->get_isp_name($value1['ispid']);
		
			?>
	<tr class="no_border">
	<td><?php echo $row[0];?></td><td><?php echo $this->get_country_name($row[1]);?></td>
	</tr>
	<tr class="no_border"><td><br></td></tr>
	<?php }
	} ?>
</table>
