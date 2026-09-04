<table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0">

<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	
<tr class="no_border"><td height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('targeted languages of',array('x'=>$this->get_variable('adname')));?></td></tr>
	<?php
	$numbers=$this->get_variable('numbers');

	if($numbers==0) {?>
	<tr class="no_border"><td  height="40px"><?php echo $this->get_label('all language targeted');?></td></tr>
	<?php }
	
	else{	$res=$this->get_result('res');
		
		foreach($res as $key1=>$value1)
		{?>
	<tr class="no_border">
	<td ><?php echo $this->get_language_name($value1['language_id']);?></td>
	</tr>
	<?php }
	} ?>
</table>
