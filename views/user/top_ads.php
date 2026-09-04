<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>

<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery-ui-1.8.23.custom.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/common.js'></script>

<?php
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
$active_theme=Configuration::get_instance()->read('active_theme');
?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo THEME_DIR_PATH.$active_theme;?>/css/style.css" />

<link href="//fonts.googleapis.com/css?family=Oswald|Raleway" rel="stylesheet">

<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo COMMON_DIR_PATH;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>


<style type="text/css">
.data_table
{
	border-left: 1px solid #CCCCCC;
}

<?php if($direction ==1) {?>
.report_main_table_data1
{
	float:left;
}
<?php }?>

.report_main_table_tab
{
	width: 100px;
}

.btn
{
	padding: 4px 6px;
}
	

@media (min-width: 470px) and (max-width: 847px)
{
	.report_main_table_tab
	{
		width: 65px;
	}
	
	#duration
	{
		width:125px !important;
	}
}


@media (max-width: 469px)
{	
	.report_main_table_tab
	{
		width: 35px;
	}
	
	#duration
	{
		width:80px !important;
	}
	
	.btn
	{
		padding: 4px 4px;
	}
	
}
</style>
</head>
<body>

<?php 
$cpc_enabled=$this->get_addon_status('cpc_enabled');
$cpa_enabled=$this->get_addon_status('cpa_enabled');
$cpm_enabled=$this->get_addon_status('cpm_enabled');
$pop_enabled=$this->get_addon_status('pop-ads_enabled');
$sponsored_enabled=$this->get_variable('sponsored_enabled');
$affiliate_enabled=$this->get_addon_status('affiliate-ads_enabled');
$cpv_enabled=$this->get_addon_status('video-ads_enabled');
?>

<script type="text/javascript">

function show_tab(id)
{
	$('.tabcontent').css('display','none');
	$('.report_main_table_tab').removeClass('report_main_table_tab_temp');

	$('#showstat'+id).css('display','block');
	
	$('#tab_'+id).addClass('report_main_table_tab_temp');


	if($(window).width() < 768)
	{
		trlength=$('#table-desktop'+id+' tr').length; 
	
		if(trlength >0)
		trlength=trlength-1;

		if(id ==6)
		heightdata=trlength*470;
		else if(id ==12)
		heightdata=trlength*400;
		else
		heightdata=trlength*360;
	
		parent.document.getElementById('top-ad-iframe1').style.height=heightdata+"px";
		parent.document.getElementById('top-ad-div1').style.height=heightdata+"px";
	}
	else
	{
		parent.document.getElementById('top-ad-iframe1').style.height="325px";
		parent.document.getElementById('top-ad-div1').style.height="325px";
	}
		
}


$(document).ready(function() {

	  <?php if($cpc_enabled ==1){?>   
	  show_tab(0);
	  <?php }else if($cpm_enabled ==1){?> 
	  show_tab(1);
	  <?php }else if($cpa_enabled ==1){?>   
	  show_tab(6);
	  <?php }else if($sponsored_enabled ==1){?> 
	  show_tab(3);
	  <?php }else if($pop_enabled ==1){?> 
	  show_tab(9);
	  <?php }else if($affiliate_enabled ==1){?> 
	  show_tab(12);
	  <?php }else if($cpv_enabled ==1){?> 
	  show_tab(13);	  		  
	  <?php }?> 

	

	<?php if($cpc_enabled ==1){?>
	CreateResponsiveTable('table-desktop0');
	<?php }?>

	<?php if($cpm_enabled ==1){?>
	CreateResponsiveTable('table-desktop1');
	<?php }?>
	<?php if($cpa_enabled ==1){?>
	CreateResponsiveTable('table-desktop6');
	<?php }?>

	<?php if($pop_enabled ==1){?>
	CreateResponsiveTable('table-desktop9');
	<?php }?>

	<?php if($affiliate_enabled ==1){?>
	CreateResponsiveTable('table-desktop12');
	<?php }?>

	<?php if($cpv_enabled ==1){?>
	CreateResponsiveTable('table-desktop13');
	<?php }?>		
	
	$(window).resize(function()
	{
		selectid=$('.report_main_table_tab_temp').attr('id');
		selectarray=selectid.split('_');

		if(selectarray.length >1)
		show_tab(selectarray[1]);	

		<?php if($cpc_enabled ==1){?>
	 	CreateResponsiveTable('table-desktop0');
		<?php }?>

	 	<?php if($cpm_enabled ==1){?>
		CreateResponsiveTable('table-desktop1');
		<?php }?>
		<?php if($cpa_enabled ==1){?>
		CreateResponsiveTable('table-desktop6');
		<?php }?>

		<?php if($pop_enabled ==1){?>
		CreateResponsiveTable('table-desktop9');
		<?php }?>

		<?php if($affiliate_enabled ==1){?>
		CreateResponsiveTable('table-desktop12');
		<?php }?>

		<?php if($cpv_enabled ==1){?>
		CreateResponsiveTable('table-desktop13');
		<?php }?>			
	});
});
</script>



<?php 


$uid=$this->get_variable('uid');
$duration=$this->get_variable('duration');
?>



<h2 class="table_head"><?php echo $this->get_label('your top ads of');?></h2>
<div class="page_heading-btm"></div>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 padding-side">
<div class="col-lg-8 col-sm-8 col-md-8 col-xs-7 padding-side">

  <ul style="list-style-type: none;">
  <?php if($cpc_enabled ==1){?>   
  <li class="report_main_table_tab" id="tab_0" onclick="show_tab(0);"><?php echo $this->get_label('cpc');?></li>
  <?php }?>
  
  <?php if($cpm_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_1" onclick="show_tab(1);"><?php echo $this->get_label('cpm');?></li>
  <?php }?>
  
  <?php if($cpa_enabled ==1){?>   
  <li class="report_main_table_tab" id="tab_6" onclick="show_tab(6);"><?php echo $this->get_label('cpa');?></li>
  <?php }?>
  
  <?php if($cpv_enabled ==1){?>   
  <li class="report_main_table_tab" id="tab_13" onclick="show_tab(13);"><?php echo $this->get_label('cpv');?></li>
  <?php }?>  
  
  <?php if($pop_enabled ==1){?> 
  <li class="report_main_table_tab" id="tab_9" onclick="show_tab(9);"><?php echo $this->get_label('pop');?></li>
  <?php }?>  
  
  <?php if($affiliate_enabled ==1){?> 
  <li class="report_main_table_tab" style="min-width: 60px;" id="tab_12" onclick="show_tab(12);"><?php echo $this->get_label('affiliate');?></li>
  <?php }?>  
    
  </ul>
</div>  
<div class="col-lg-4 col-sm-4 col-md-4 col-xs-5 padding-side">  


<?php 
$form2=$this->create_form();
$form2->start("overall_top","","post");
?>
  
<div style="float: right;text-align:right;margin-bottom: 2px;">
<span style="float: left;">
<select class="form-control" name="duration" id="duration" style="width: 150px;margin-right: 5px;">
<option value="3" <?php if($duration==3) { echo "selected"; } ?>><?php echo $this->get_label('last 30');?></option>
<option value="4" <?php if($duration==4) { echo "selected"; } ?>><?php echo $this->get_label('last 12 month');?></option>
<option value="5" <?php if($duration==5) { echo "selected"; } ?>><?php echo $this->get_label('all time');?></option>
</select>
</span>
 
<span style="float: left;">
<input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
</span>
</div>
<?php $form2->end(); ?> 	
</div>  
</div>


<?php if($cpc_enabled ==1){?> 
<div id="showstat0" class="tabcontent">  
<?php $this->dispatch("cpc/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/cpc/');?>
</div>
<?php }?>


<?php if($cpm_enabled ==1){?>
<div id="showstat1" class="tabcontent">  
<?php $this->dispatch("cpm/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/cpm/');?>
</div>
<?php }?>

<?php if($cpa_enabled ==1){?>
<div id="showstat6" class="tabcontent">  
<?php $this->dispatch("cpa/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/cpa/');?>
</div>
<?php }?>


<?php if($cpv_enabled ==1){?>
<div id="showstat13" class="tabcontent">  
<?php $this->dispatch("cpv/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/video-ads/');?>
</div>
<?php }?>



<?php if($pop_enabled ==1){?>
<div id="showstat9" class="tabcontent">  
<?php $this->dispatch("pop/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/pop-ads/');?>
</div>
<?php }?>

<?php 
if($affiliate_enabled ==1){?>
<div id="showstat12" class="tabcontent">  
<?php $this->dispatch("affiliate/top_list/".$duration,PATH_TO_ROOT.ADDON_DIR.'/affiliate-ads/');?>
</div>
<?php }?>

</body>
</html>