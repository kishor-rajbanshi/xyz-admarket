<?php $this->dispatch("layout/header_iframe", PATH_TO_ROOT);?>

<?php
$message   = $this->get_variable("message");
$aid       = $this->get_variable('aid');
$olddevice = $this->get_variable('olddevice');

$res_os=$this->get_result('res_os');
$res_browser=$this->get_result('res_browser');
$osarrayjson=$this->get_variable('osarrayjson');
$osarray=json_decode($osarrayjson);
$osdb=json_decode($this->get_variable('osdb'));
$brdb=json_decode($this->get_variable('brdb'));
$os_enabled=$this->get_variable('os_enabled');
$browser_enabled=$this->get_variable('browser_enabled');
?>

<style type="text/css">

.body-section {
    background-color: #ffffff;
}

</style>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 device-targeting">

<h2 class="section-heading"><?php echo $this->get_label('device targeting');?></h2>

<?php
$form=$this->create_form();
$form->start("devicetargeting","","post");

if($message != ""){?>
	<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message "><?php echo $message;?></div>
<?php }?>

<div class="row m-0">

	<div class="col-md-3 col-sm-4 col-xs-12 form-check mb-2">
		<input class="form-check-input mt-1" type="radio" name="device_type" id="device_type2" value="2" <?php if($olddevice ==2){?>checked="checked"<?php }?> onClick="LoadSubDevice(2);" />
		<label class="form-check-label"><?php echo $this->get_label('all devices');?></label>
	</div>

	<div class="col-md-3 col-sm-4 col-xs-12 form-check mb-2">
		<input class="form-check-input mt-1" type="radio" name="device_type" id="device_type0" value="0" <?php if($olddevice ==0){?>checked="checked"<?php }?> onClick="LoadSubDevice(0);" />
		<label class="form-check-label"><?php echo $this->get_label('desktop');?></label>
	</div>

	<div class="col-md-3 col-sm-4 col-xs-12 form-check mb-2">
		<input class="form-check-input mt-1" type="radio" name="device_type" id="device_type1" value="1" <?php if($olddevice ==1){?>checked="checked"<?php }?> onClick="LoadSubDevice(1);" />
		<label class="form-check-label"><?php echo $this->get_label('mobile device');?></label>
	</div>

	<input type="hidden" id="type" name="type" value="<?php echo $olddevice;?>" />
</div>

<div class="row">
	<?php if($os_enabled == 1){?>
	<div class="<?php if($browser_enabled ==1){?>col-lg-6 col-md-6 col-sm-6 col-xs-6<?php } else {?>col-lg-12 col-md-12 col-sm-12 col-xs-12<?php }?>">
	<h5 class="section-sub-heading"><?php echo $this->get_label('operating systems');?></h5>

		<div class="row m-0">
			<?php foreach ($res_os as $oskey => $osval){

				if($osval['desktop'] == 1){?>
					<div class="form-check os-box-inner <?php if($browser_enabled == 1) {?>col-lg-4 col-md-6 col-sm-6 col-xs-12 box-both<?php } else {?>col-lg-3 col-md-4 col-sm-4 col-xs-6 <?php }?> checkboxos des">
						<input class="form-check-input mt-1 os des1" type="checkbox" onClick="show_browsers();" name="os_des_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb) || $os_enabled==0 ){echo "checked";}?> />
						<label class="form-check-label"><?php echo $osval['name'];?></label>
					</div>
				<?php }	else { ?>
					<div class="form-check os-box-inner <?php if($browser_enabled ==1) {?>col-lg-4 col-md-6 col-sm-6 col-xs-12 box-both<?php } else {?>col-lg-3 col-md-4 col-sm-4 col-xs-6 <?php }?> checkboxos mob">
						<input class="form-check-input mt-1 os mob1" type="checkbox" onClick="show_browsers();" name="os_mob_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb)|| $os_enabled==0 ){echo "checked";}?> />
						<label class="form-check-label"><?php echo $osval['name'];?></label>
					</div>
				<?php }
			}?>
		</div>
	</div>
	<?php }?>

	<?php if($browser_enabled ==1){?>
		<div class="<?php if($os_enabled ==1){?>col-lg-6 col-md-6 col-sm-6 col-xs-6<?php } else {?>col-lg-12 col-md-12 col-sm-12 col-xs-12<?php }?>">
			<h5 class="section-sub-heading"><?php echo $this->get_label('browsers');?></h5>

			<div class="row m-0">
				<?php foreach ($res_browser as $bkey => $bval){?>
					<div id="brl_<?php echo $bval['id'];?>" class="form-check browser-box-inner <?php if($os_enabled ==1) {?>col-lg-4 col-md-6 col-sm-6 col-xs-12 box-both<?php } else {?>col-lg-3 col-md-4 col-sm-6 col-xs-6<?php }?> checkboxbr">
						<input type="checkbox" class="form-check-input mt-1 check_br" name="br_ids[]"  id="br_<?php echo $bval['id'];?>" value="<?php echo $bval['id'];?>" <?php if(in_array($bval['id'], $brdb)){echo "checked";}  ?>/>
						<label class="form-check-label"><?php echo $bval['name'];?></label>
					</div>
				<?php }?>
			</div>
		</div>
	<?php }?>
</div>

<div class="form-group col-md-12 col-sm-12 col-xs-12 text-center">
	<input type="hidden" name="aid" value="<?php echo $aid;?>" />
	<input class="submit-button" type="submit" name="keysubmit" value="<?php echo $this->get_label('update');?>" />
</div>

<?php $form->end(); ?>
</div>

<script type="text/javascript">
function LoadSubDevice(type)
{
	type1='<?php echo $olddevice;?>';
	if(type1==type)
		$('.checkboxbr').show();
	else {$('.checkboxbr').hide();

	}
	if(type ==1)
	{
		$('.mob').show();
		$('.des').hide();
		$('#type').val(1);
	}
	else if(type==2)
	{
		$('.des').show();
		$('.mob').show();
		$('#type').val(2);
	}
	else
	{
		$('.des').show();
		$('.mob').hide();
		$('#type').val(0);
	}

	<?php if($os_enabled ==1 || $browser_enabled ==1) {?>
	show_browsers();
	<?php }?>
}
function show_browsers()
{
	type1=$('#type').val();

	$('.checkboxbr').hide();
	osarrayjson='<?php echo $osarrayjson;?>';
	var js = JSON.parse(osarrayjson);


	numberOfChecked=0;
    if(type1 ==0)
    {
	  	 numberOfChecked = $('.des1:checked').length;
	  	 if(numberOfChecked >0 )
	  	 {
		    $('.des1:checkbox:checked').each(function()
		    {
		      id	= this.id;
	     	  arr	= id.split('_');
			  osid	= arr[1];

			  if(osid > 0)
			  {
			  try
			  {
			     	for(i=0;i < js['os_'+osid].length;i++)
			  	 	{
			  			$('#brl_'+js['os_'+osid][i]).show();
			  	 	}
			   }
			   catch (e) {};
			}
			});
		 }
	  	 else
	  	 {
	  		 $('.des1:checkbox').each(function()
	  		 {
		       id	= this.id;
	     	   arr	= id.split('_');
			   osid = arr[1];

			  if(osid > 0)
			  {
			   try
			   {
			       for(i=0;i < js['os_'+osid].length;i++)
			  	   {
			  		  $('#brl_'+js['os_'+osid][i]).show();
			  	   }
			   }
			   catch (e) {};
			}
		    });
	  	 }
   }
   else if(type1 ==1)
   {
	   numberOfChecked = $('.mob1:checked').length;
	   if(numberOfChecked >0)
	   {
		   $('.mob1:checkbox:checked').each(function()
		   {
		       id	= this.id;
			   arr	= id.split('_');
			   osid	= arr[1];

			  if(osid > 0)
			  {
			   try
			   {
			       for(i=0;i < js['os_'+osid].length;i++)
			  	   {
			  			$('#brl_'+js['os_'+osid][i]).show();
			  	   }
			   }
			   catch (e) {};
			}
		    });
	   }
	   else
	   {
		   $('.mob1:checkbox').each(function() {

		    	 id		= this.id;
			     arr	= id.split('_');
			     osid	= arr[1];

			  if(osid > 0)
			  {
			     try
			     {
				     for(i=0;i < js['os_'+osid].length;i++)
				  	 {
				  		$('#brl_'+js['os_'+osid][i]).show();
				  	 }
				 }
				 catch (e) {};
				}
		    });
	   }
   }
   else
   {
	   numberOfChecked = $('.os:checked').length;

	   $('.os:checkbox:checked').each(function()
	   {
	      id	= this.id;
	      arr	= id.split('_');
	      osid	= arr[1];

			  if(osid > 0)
			  {
		  try
		  {
			  for(i=0;i < js['os_'+osid].length;i++)
			  {
			 	  $('#brl_'+js['os_'+osid][i]).show();
			  }
		   }
		   catch (e) {};
		}
	    });
   }

   if(numberOfChecked ==0)
   $('.checkboxbr').show();


}
<?php if($os_enabled ==1 || $browser_enabled ==1 ) {?>
//show_browsers();
<?php }?>
LoadSubDevice('<?php echo $olddevice;?>');
</script>
<?php $this->dispatch("layout/footer_iframe", PATH_TO_ROOT);?>
