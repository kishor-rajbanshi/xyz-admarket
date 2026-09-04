<?php
$adname=$this->get_variable('adname');
$osenabled=$this->get_variable('osenabled');
$browsrenabled=$this->get_variable('browserenabled');
$device=$this->get_variable('device');
?>
<div class="row">
<div class="col-md-6 col-sm-12 col-xs-6">
<h2 class="section-sub-heading"><?php echo $this->get_label('device type');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12 box_div">
	<?php
	if($device == 2)
	echo $this->get_label('all devices');
	elseif ($device == 1)
	echo $this->get_label('mobile device');
	else
	echo $this->get_label('desktop');
	?>
</div>

<?php if($osenabled == 1){?>
	<h2 class="section-sub-heading"><?php echo $this->get_label('os');?></h2>

	<?php
	$numbers = $this->get_variable('osnumbers');

	if($numbers == 0){?>
		<div class="col-md-12 col-sm-12 col-xs-12"><?php echo $this->get_message('all os targeted');?></div>
	<?php	}	else {
			$res = $this->get_result('res_os'); ?>
			<div class="row box_div m-0">
				<?php foreach($res as $key1=>$value1){?>
					<div class="col-md-4 col-sm-12 col-xs-4 py-1"><i class="fa fa-dot-circle-o" aria-hidden="true"></i>
		 				<?php echo $this->get_os_name($value1['osid']);?>
		 			</div>
				<?php
				}?>
			</div>
			<?php
		}
}?>
</div>

<div class="col-md-6 col-sm-12 col-xs-6">
<?php	if($browsrenabled==1){?>
	<h2 class="section-sub-heading"><?php echo $this->get_label('browser');?></h2>

	<?php
	$numbers1 = $this->get_variable('brnumbers');

	if($numbers1 == 0){?>
      <div class="col-md-12 col-sm-12 col-xs-12">
        <?php echo $this->get_message('all browser targeted'); ?>
      </div>
    <?php } else { ?>
      <div class="row box_div m-0">
	<?php

	$resbr=$this->get_result('res_browser');
        foreach ($resbr as $key => $value) { ?>
          <div class="col-md-4 col-sm-12 col-xs-4  py-1">
            <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
 	    <?php echo $this->get_browser_name($value['browserid']); ?>
          </div>
        <?php } ?>
      </div>
    <?php } ?>
  <?php } ?>
</div>

</div>
