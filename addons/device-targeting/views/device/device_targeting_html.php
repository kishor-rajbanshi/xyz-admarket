<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
</head>
<body style="background: none;background-color: #FFFFFF;">
<?php
$message=$this->get_variable("message");
$aid=$this->get_variable('aid');

$olddevice=$this->get_variable('olddevice');


$res_os=$this->get_result('res_os');
$res_browser=$this->get_result('res_browser');
$osarrayjson=$this->get_variable('osarrayjson');
$osarray=json_decode($osarrayjson);
$osdb=json_decode($this->get_variable('osdb'));
$brdb=json_decode($this->get_variable('brdb'));
$os_enabled=$this->get_variable('os_enabled');
$browser_enabled=$this->get_variable('browser_enabled');

?>
<div style="margin-top: 5px;padding:5px;">
<div class="heading-underline"><?php echo $this->get_label('manage device targeting');?></div>

<?php
$form=$this->create_form();
$form->start("devicetargeting","","post");
?>

<?php if($message !=''){?>
<div style="color: red;"><?php echo $message;?></div>
<?php }?>

<div>
	<input type="radio" name="device_type" id="device_type2" value="2" <?php if($olddevice ==2){?>checked="checked"<?php }?> onclick="LoadSubDevice(2);" />&nbsp;<?php echo $this->get_label('all devices');?>

	<input type="radio" name="device_type" id="device_type0" value="0" <?php if($olddevice ==0){?>checked="checked"<?php }?> onclick="LoadSubDevice(0);" />&nbsp;<?php echo $this->get_label('desktop');?>

	<input type="radio" name="device_type" id="device_type1" value="1" <?php if($olddevice ==1){?>checked="checked"<?php }?> onclick="LoadSubDevice(1);" />&nbsp;<?php echo $this->get_label('mobile device');?>

	<input type="hidden" id="type" name="type" value="<?php echo $olddevice?>" />
</div>


<div class="os" style="<?php if(Configuration::get_instance()->read('os-targeting_enabled')==0){?> display:none; <?php }?> <?php if($browser_enabled ==1) {?> width:50%;float:left;<?php } else {?>width:100%;float:left;<?php }?>" >
<h5 class="heading-underline" style="max-width: 100%;margin-top:10px;"><?php echo $this->get_label('Operating Systems');?></h5>
<div style="background-color: #ffffff !important;color: #123E64 !important;">
<?php foreach ($res_os as $oskey => $osval){

	if($osval['desktop']==1)
	{
	?>
	<div style="<?php if($browser_enabled==1) {?>width:33%;float:left;<?php } else {?>width:25%;float:left;<?php }?>" class="language_div_inner  checkboxos des"><label><input class="os des1" type="checkbox" onclick="show_browsers(<?php echo $osval['id'];?>)" name="os_des_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb) || $os_enabled==0 ){echo "checked";}?>/><?php echo $osval['name'];?></label></div>
	<?php
	}
	else {?>
	<div style="<?php if($browser_enabled==1) {?>width:33%;float:left;<?php } else {?>width:25%;float:left;<?php }?>" class="language_div_inner  checkboxos mob"><label><input class="os mob1" type="checkbox" onclick="show_browsers(<?php echo $osval['id'];?>)" name="os_mob_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb)|| $os_enabled==0 ){echo "checked";}?>/><?php echo $osval['name'];?></label></div>
	<?php
	}

}?>

</div>
</div>
<?php

 if(Configuration::get_instance()->read('browser-targeting_enabled')==1){

?>
<div class="browser" style="<?php if($os_enabled ==1) {?> width:50%;float:left;<?php } else {?>width:100%;float:left;<?php }?>" >
<h5 class="heading-underline"  style="max-width: 100%;margin-top:10px;"><?php echo $this->get_label('Browsers');?></h5>
<div class="form-group" style="background-color: #ffffff !important;color: #123E64 !important;">
<?php foreach ($res_browser as $bkey => $bval){?>
<div id="brl_<?php echo $bval['id'];?>" style="<?php if($os_enabled==1) {?>width:33%;float:left;<?php } else {?>width:25%;float:left;<?php }?>" class="language_div_inner checkboxbr">
	<input type="checkbox" class="check_br" name="br_ids[]"  id="br_<?php echo $bval['id'];?>" value="<?php echo $bval['id'];?>" <?php if(in_array($bval['id'], $brdb)){echo "checked";}  ?> /><?php echo $bval['name'];?>
</div>
<?php }?>

</div>
</div>
<?php }?>

<div style="margin-bottom:10px; margin-top:5px;">
	<input type="hidden" name="aid" value="<?php echo $aid?>" />
	<input style="position: fixed;bottom: 15px;left: 50%;" type="submit" name="keysubmit" value="<?php echo $this->get_label('update');?>" />
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
</body>
</html>
