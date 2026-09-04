<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>

<?php 
$active_theme=$this->read_cookie_param('active_theme');
if($active_theme=="")
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
</head>
<body>
<?php 
$row=$this->get_result('row');
$oldcat_list=$this->get_variable('oldcat_list');
$alert_msg=$this->get_variable('alert_msg');
$aid=$this->get_variable('aid');
?>
<script type="text/javascript">
function CheckOldSelection()
{
	ckdata='<?php echo $oldcat_list;?>';
	ckdata_arr=ckdata.split('_');

	for(i=0;i< ckdata_arr.length;i++)
    {
		if(ckdata_arr[i] !="")
		{
			if(document.getElementById('ca'+ckdata_arr[i]))
			document.getElementById('ca'+ckdata_arr[i]).checked=true;
		}
    }
}
</script>

<?php 
if($oldcat_list =='')
$alert_msg=$this->get_message('all category targeted');
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="checkbox_head"><?php echo $this->get_label('category targeting');?></div>


<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div">

<div class="col-md-12 col-sm-12 col-xs-12 frame_td_msg1"><?php echo $alert_msg;?></div>




<?php 
$form=$this->create_form();
$form->start("category_targeting","","post");
?>



<?php 
$count=count($row);
$row_count=ceil($count/4);

$i=1;
foreach($row as $key=>$value)
{
	$childcount=CategoryHelper::get_category_child_count($value['id']);
	
	if($i==1){?>
	
	
	<div class="col-md-12 col-sm-12 col-xs-12 category_div_outer padding-side">

	<?php }?>
	
	
	
	<div class="col-md-3 col-sm-6 col-xs-12 category_div_inner">
	<input type="checkbox" name="ca<?php echo $value['id'];?>" id="ca<?php echo $value['id'];?>" value="1" <?php if($childcount >0){?>disabled="disabled"<?php }?> /><?php echo $value['name'];?>
	<div><?php echo CategoryHelper::get_category_child_list($value['id']);?></div>
	</div>
	
	<?php if($i % 4 ==0){?>
	</div>
	<?php if($i < $count){?>
	<div class="col-md-12 col-sm-12 col-xs-12 category_div_outer padding-side" style="border-top:1px solid #cccccc;">
	<?php }}
	if($i == $count){?>
	</div>
	<?php }?>
	<?php 
	$i=$i+1;
}
?>

<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />
<input class="btn btn-primary btn-lg" style="position: fixed;bottom: 2px;left:50%;" type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td></tr>  

<?php $form->end(); ?>

</div></div>


</div>

<?php if($oldcat_list !=''){?>
<script type="text/javascript">CheckOldSelection();</script>
<?php }?>
</body>
</html>