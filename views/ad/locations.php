<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/common.js"></script>

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
	$active_theme=Configuration::get_instance()->read('active_theme');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>font-awesome/css/font-awesome.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />


<?php 
$message=$this->get_variable('message');
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>

</head>
<body>

<script type="text/javascript">

function addOption(text,value)
{
	var optn = document.createElement("option");
	optn.text = text;
	optn.value = value;
	document.getElementById('loc').options.add(optn);
	
}

function addCountry(text,value)
{
	var optn1 = document.createElement("option");
	optn1.text = text;
	optn1.value = value;
	document.getElementById('countries').options.add(optn1);
	
}

function addOption_list()
{
	var loctn=document.loca.countries.options;
	for(i=loctn.length-1;i>=0;i--) 
	{
	if(loctn[i].selected)
	    {
		addOption( loctn[i].text, loctn[i].value);
		document.loca.countries.remove(i);
		}
	}

	$('#a_loc').val('');

	var locs=document.loca.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.loca.loc.options[j].value+",");
	}
	sortSelect(document.loca.loc);
}

function deleteOption_list()
{
	var delt=document.loca.loc.options; 
	for(i=delt.length-1;i>=0;i--)
	{
	if(delt[i].selected)
		{
		addCountry(delt[i].text, delt[i].value);
		document.loca.loc.remove(i);
		}
	}
	
	$('#a_loc').val('');



	var locs=document.loca.loc.options;
	for(j=0;j<locs.length;j++)
	{
		$('#a_loc').val($('#a_loc').val()+document.loca.loc.options[j].value+",");
	}
	sortSelect(document.loca.countries);
}
</script>

<?php 	
$result=$this->get_result('result');

$aid=$this->get_variable('aid');
$tar_count=$this->get_variable('tar_count');

$form=$this->create_form();
$form->start("loca",$this->make_url("ad/locations/").$aid,"post"); 

$result3=$this->get_result('result3');
?>


<div class="col-md-12 col-sm-12 col-xs-12">
<div class="checkbox_head"><?php echo $this->get_label('location msg');?></div>




<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div">

<?php if($tar_count ==0){?>	
<div class="col-md-12 col-sm-12 col-xs-12 notification" style="text-align: right;"><?php echo $this->get_label('no location');?></div>
<?php }else if($message !=''){?>
<div class="col-md-12 col-sm-12 col-xs-12 frame_td_msg1" id="main_alert"><?php echo $message;?></div>
<?php }?>



<div style="height: 10px;"></div>

<div class="col-md-5 col-sm-5 col-xs-5">




<div class="form-group">

<select class="form-control country_list" name="countries" id="countries" multiple="multiple">
<?php foreach($result as $key=>$value){?>
<option value="<?php echo $value['code'];?>"><?php echo $value['name'];?></option>
<?php }?>
</select></div>
</div>


<div class="col-md-2 col-sm-2 col-xs-2" style="padding-top:60px;">
<div style="width:33px; min-height:100px; margin:auto;">

<?php if($direction ==1) {?>
<div onclick="addOption_list()" class="location-add"><i class="fa fa-arrow-circle-left"></i></div>
<div onclick="deleteOption_list()" class="location-add"><i class="fa fa-arrow-circle-right"></i></div>
<?php }else{?>
<div onclick="addOption_list()" class="location-add"><i class="fa fa-arrow-circle-right"></i></div>
<div onclick="deleteOption_list()" class="location-add"><i class="fa fa-arrow-circle-left"></i></div>
<?php }?>
</div></div>


<div class="col-md-5 col-sm-5 col-xs-5">
<div class="form-group">
<select class="form-control country_list" id="loc" name="loc" multiple="multiple">
<?php 
$code_str="";
foreach($result3 as $key=>$val){
	
$code_str.=$val['country_code'].","	;
	
$country_name=$this->get_country_name($val['country_code']);
?>
<option value="<?php echo $val['country_code'];?>"><?php echo $country_name;?></option>
<?php }?>
</select>
 
<input type="hidden" name="a_loc" id="a_loc" value="<?php echo $code_str;?>">
<input type="hidden" name="aid" value="<?php echo $aid?>">

</div>
</div>




<div>

<div class="col-md-12 col-sm-12 col-xs-12">

<div class="form-group" style="margin:auto; width:128px;">
<input class="btn btn-primary btn-lg" type="submit" name="submit" value="<?php if($tar_count ==0){echo $this->get_label('add location');}else{echo $this->get_label('update');}?>"></div>
</div></div>
</div></div>

</div>

<?php $form->end(); ?>
<script type="text/javascript">
sortSelect(document.getElementById('loc'));
</script>	
</body>
</html>