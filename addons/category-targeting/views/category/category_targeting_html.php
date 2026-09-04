<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
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

<table cellpadding="0" cellspacing="0" border="0">
<tr><td height="5px" ></td></tr>
<tr><td height="10px" colspan="2"><span style="color: red;padding-left: 10px;"><?php echo $alert_msg;?></span></td></tr>

<tr><td height="5px" > </td></tr>
<tr><td>
<?php 
$form=$this->create_form();
$form->start("category_targeting","","post");
?>

<table style="width: 100%" cellpadding="0" cellspacing="0">
<tr>
<td colspan="4">
<?php 
$count=count($row);
$row_count=ceil($count/4);

$i=1;
foreach($row as $key=>$value)
{
	$childcount=CategoryHelper::get_category_child_count($value['id']);
	
	if($i==1){?>
	<div class="category_div_outer">
	<?php }?>
	
	
	
	<div class="category_div_inner">
	<input type="checkbox" name="ca<?php echo $value['id'];?>" id="ca<?php echo $value['id'];?>" value="1" <?php if($childcount >0){?>disabled="disabled"<?php }?> /><?php echo $value['name'];?>
	<div style="margin-left: 20px;"><?php echo CategoryHelper::get_category_child_list($value['id']);?></div>
	</div>
	
	<?php if($i % 4 ==0){?>
	</div>
	<?php if($i < $count){?>
	<div class="category_div_outer">
	<?php }}
	if($i == $count){?>
	</div>
	<?php }?>
	<?php 
	$i=$i+1;
}
?>
</td></tr>

<tr><td colspan="4" align="center" height="50px;"></td></tr>
<tr><td colspan="4" align="center">
<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />
<input style="position: fixed;bottom: 2px;" type="submit" name="Submit" value="<?php echo $this->get_label('submit'); ?>"></td></tr>  
</table>
<?php $form->end(); ?>
</td>
</tr>
<tr><td height="10px" ></td></tr>

</table>
<?php if($oldcat_list !=''){?>
<script type="text/javascript">CheckOldSelection();</script>
<?php }?>
</body>
</html>