<?php 
$adname=$this->get_variable('adname');
$osenabled=$this->get_variable('osenabled');
$browsrenabled=$this->get_variable('browserenabled');
?>
<div class="col-md-4 col-sm-4 col-xs-12">
	<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:20px;padding-top:20px;"><u><b><?php echo $this->get_label('device type');?></b></u></div>
	<?php
		$olddesktop=$this->get_variable('olddesktop');
		$oldmobile=$this->get_variable('oldmobile');
		$oldandriod=$this->get_variable('oldandriod');
		$oldios=$this->get_variable('oldios');
		$oldwindows=$this->get_variable('oldwindows');
		$device=$this->get_variable('device');
	?>
		
	<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php if($device ==2){echo $this->get_label('all devices');} elseif ($device ==1){ echo $this->get_label('mobile device');} else{ echo $this->get_label('desktop');}?>	</div>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
	<?php if($osenabled==1)
	{?>
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:20px;padding-top:20px;"><u><b><?php echo $this->get_label('os');?></b></u></div>
		<?php 
		
		$numbers=$this->get_variable('osnumbers');

		if($numbers==0) 
		{?>
			<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_message('all os targeted');?></div>
		<?php 
		}
	
		else
		{	
			$res=$this->get_result('res_os');
		
			foreach($res as $key1=>$value1)
			{?>
				<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_os_name($value1['osid']);?></div>
				<?php 
			}
		}
		
	} ?>
</div>
<div class="col-md-4 col-sm-4 col-xs-12">
<?php 
	if($browsrenabled==1)
	{
		?>
		<div class="col-md-12 col-sm-12 col-xs-12" style="padding-left:20px;padding-top:20px;"><u><b><?php echo $this->get_label('browser');?></b></u></div>
		
		<?php 
		$numbers1=$this->get_variable('brnumbers');

		if($numbers1==0)
		 {?>
			<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_message('all browser targeted');?></div>
		<?php 
		 }
	
		else
		{	
			$resbr=$this->get_result('res_browser');
		
			foreach($resbr as $key=>$value)
			{?>
				<div class="col-md-12 col-sm-12 col-xs-12"  style="padding-left:40px;padding-top:10px;"><?php echo $this->get_browser_name($value['browserid']);?></div>
			<?php 
			}
		} 
	}?>
</div>
	

