<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<?php
$active_theme=$this->read_cookie_param('active_theme');

if($active_theme =="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>

<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>

<style>

.click_div_bg
{
    max-width: 100%;
    background-color: rgba(102, 102, 102, 0.49);
}

</style>


</head>
<body>
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
<div class="col-md-12 col-sm-12 col-xs-12" style="margin-top: 5px;">
<div class="checkbox_head"><?php echo $this->get_label('manage device targeting');?></div>

<?php
$form=$this->create_form();
$form->start("devicetargeting","","post"); 
?>
<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">

<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="padding-top: 10px;">

<?php if($message !=''){?>
<div class="col-md-12 col-sm-12 col-xs-12 padding-side" id="main_alert" style="color: red;"><?php echo $message;?></div>
<?php }?>

<div class="col-md-12 col-sm-12 col-xs-12">


<div class="col-md-12 col-sm-12 col-xs-12 device-option">

<label class="col-md-3 col-sm-4 col-xs-4 device-option-label">
<input type="radio" name="device_type" id="device_type2" value="2" <?php if($olddevice ==2){?>checked="checked"<?php }?> onclick="LoadSubDevice(2);" />&nbsp;<?php echo $this->get_label('all devices');?>
</label>

<label class="col-md-3 col-sm-4 col-xs-4 device-option-label">
<input type="radio" name="device_type" id="device_type0" value="0" <?php if($olddevice ==0){?>checked="checked"<?php }?> onclick="LoadSubDevice(0);" />&nbsp;<?php echo $this->get_label('desktop');?>
</label>

<label class="col-md-3 col-sm-4 col-xs-4 device-option-label">
<input type="radio" name="device_type" id="device_type1" value="1" <?php if($olddevice ==1){?>checked="checked"<?php }?> onclick="LoadSubDevice(1);" />&nbsp;<?php echo $this->get_label('mobile device');?>
</label>

<input type="hidden" id="type" name="type" value="<?php echo $olddevice;?>" >
</div>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 padding-side">
	
<?php if($os_enabled ==1){?>



<div class="os os-box <?php if($browser_enabled ==1){?>col-lg-6 col-md-6 col-sm-6 col-xs-6<?php } else {?>col-lg-12 col-md-12 col-sm-12 col-xs-12<?php }?>">
<h5 class="click_div_bg"><?php echo $this->get_label('operating systems');?></h5>

<div class="form-group os-box-div">
<?php foreach ($res_os as $oskey => $osval){
	
	if($osval['desktop']==1){?>
	<div class="os-box-inner <?php if($browser_enabled == 1) {?>col-lg-4 col-md-4 col-sm-6 col-xs-6 box-both<?php } else {?>col-lg-2 col-md-2 col-sm-3 col-xs-4<?php }?> padding-side checkboxos des"><label><input class="os des1" type="checkbox" onclick="show_browsers();" name="os_des_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb) || $os_enabled==0 ){echo "checked";}?>/><?php echo $osval['name'];?></label></div>
	<?php }	else {?>
	<div class="os-box-inner <?php if($browser_enabled ==1) {?>col-lg-4 col-md-4 col-sm-6 col-xs-6 box-both<?php } else {?>col-lg-2 col-md-2 col-sm-3 col-xs-4<?php }?> padding-side checkboxos mob"><label><input class="os mob1" type="checkbox" onclick="show_browsers();" name="os_mob_ids[]" id="os_<?php echo $osval['id'];?>" value="<?php echo $osval['id'];?>" <?php if(in_array($osval['id'], $osdb)|| $os_enabled==0 ){echo "checked";}?>/><?php echo $osval['name'];?></label></div>
	<?php }
}?>
</div>
</div>
<?php }?>


<?php if($browser_enabled ==1){?>
<div class="browser browser-box <?php if($os_enabled ==1){?>col-lg-6 col-md-6 col-sm-6 col-xs-6<?php } else {?>col-lg-12 col-md-12 col-sm-12 col-xs-12<?php }?>">
<h5 class="click_div_bg"><?php echo $this->get_label('browsers');?></h5>

<div class="form-group browser-box-div">

<?php foreach ($res_browser as $bkey => $bval){?>
<div id="brl_<?php echo $bval['id'];?>" class="browser-box-inner <?php if($os_enabled ==1) {?>col-lg-4 col-md-4 col-sm-6 col-xs-6 box-both<?php } else {?>col-lg-2 col-md-2 col-sm-3 col-xs-4<?php }?> padding-side checkboxbr"><label ><input type="checkbox" class="check_br" name="br_ids[]"  id="br_<?php echo $bval['id'];?>" value="<?php echo $bval['id'];?>" <?php if(in_array($bval['id'], $brdb)){echo "checked";}  ?>/><?php echo $bval['name'];?></label></div>
<?php }?>
</div>
</div>
<?php }?>
</div>




<div class="frame_table">
<div class="form-group form-group-sm" style="margin-bottom:10px; margin-top:5px;">
<div class="row">

<input type="hidden" name="aid" value="<?php echo $aid;?>" />
<input class="btn btn-primary btn-lg"  style="position: fixed;bottom: 2px;left: 45%;" type="submit" name="keysubmit" value="<?php echo $this->get_label('update');?>" />

</div>
</div>
</div>
<?php $form->end(); ?>
</div> 

</div></div></div>

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

			  try
			  {   
			     	for(i=0;i < js['os_'+osid].length;i++)
			  	 	{
			  			$('#brl_'+js['os_'+osid][i]).show();
			  	 	}
			   }
			   catch (e) {};
			});	
		 }
	  	 else
	  	 {
	  		 $('.des1:checkbox').each(function() 
	  		 {
		       id	= this.id;
	     	   arr	= id.split('_');
			   osid = arr[1];

			   try
			   {
			       for(i=0;i < js['os_'+osid].length;i++)
			  	   {
			  		  $('#brl_'+js['os_'+osid][i]).show();
			  	   }
			   }
			   catch (e) {};
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

			   try
			   {  
			       for(i=0;i < js['os_'+osid].length;i++)
			  	   {
			  			$('#brl_'+js['os_'+osid][i]).show();
			  	   }
			   }
			   catch (e) {};
		    });	
	   }
	   else 
	   {
		   $('.mob1:checkbox').each(function() {
			   
		    	 id		= this.id;
			     arr	= id.split('_');
			     osid	= arr[1];

			     try
			     {
				     for(i=0;i < js['os_'+osid].length;i++)
				  	 {
				  		$('#brl_'+js['os_'+osid][i]).show();
				  	 }
				 }
				 catch (e) {};
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

		  try
		  {
			  for(i=0;i < js['os_'+osid].length;i++)
			  {
			 	  $('#brl_'+js['os_'+osid][i]).show();
			  }
		   }
		   catch (e) {};
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