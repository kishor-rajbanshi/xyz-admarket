<?php 
$adname=$this->get_variable('adname');
$osenabled=$this->get_variable('osenabled');
$browsrenabled=$this->get_variable('browserenabled');
?>

<table  style="width: 99%;margin-left: 10px;" cellpadding="0" cellspacing="0">
	<tr class="no_border"><td colspan="2" height="10px"></td></tr>
	<tr class="no_border"><td height="20px" class="heading-underline" style="padding: 0px;padding-left: 10px;"><?php echo $this->get_label('target devices of',array('x'=>$adname));?></td><td></td></tr>
	<?php	
	$device=$this->get_variable('device');
	?>		
	<tr class="no_border"><td  height="40px"><?php if($device ==2){echo $this->get_label('all devices');} elseif ($device ==1){ echo $this->get_label('mobile device');} else{ echo $this->get_label('desktop');}?></td></tr>	
				
	<?php if($osenabled==1)
	{?>
		<tr class="no_border"><td class="heading-underline"><?php echo $this->get_label('os');?></td></tr>	
		<?php 
		
		$numbers=$this->get_variable('osnumbers');

		if($numbers==0) 
		{?>
			<tr class="no_border"><td  height="40px"><?php echo $this->get_message('all os targeted');?></td></tr>
		<?php 
		}
	
		else
		{	
			$res=$this->get_result('res_os');
		
			foreach($res as $key1=>$value1)
			{?>
				<tr class="no_border">
				<td ><?php echo $this->get_os_name($value1['osid']);?></td>
				</tr>
				<?php 
			}
		}
		
	}
	if($browsrenabled==1)
	{
		?>
		<tr class="no_border"><td class="heading-underline"><?php echo $this->get_label('browser');?></td></tr>	
		
		<?php 
		$numbers1=$this->get_variable('brnumbers');

		if($numbers1==0)
		 {?>
			<tr class="no_border"><td  height="40px"><?php echo $this->get_message('all browser targeted');?></td></tr>
		<?php 
		 }
	
		else
		{	
			$resbr=$this->get_result('res_browser');
		
			foreach($resbr as $key=>$value)
			{?>
				<tr class="no_border">
				<td ><?php echo $this->get_browser_name($value['browserid']);?></td>
				</tr>
			<?php 
			}
		} 
	}?>
	
	
</table>
