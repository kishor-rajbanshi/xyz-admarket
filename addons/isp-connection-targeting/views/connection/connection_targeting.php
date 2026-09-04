<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" src="<?php echo COMMON_DIR_PATH;?>js/jquery.min.js"></script>
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/bootstrap.min.js'></script>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-style.css" />


<?php 
$direction=$this->get_variable('direction');
if($direction ==1) {?>
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/bootstrap-rtl.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo BASE;?>css/public-rtl-style.css" />
<?php }?>
</head>
<body>
<?php 
$row=$this->get_result('row');
$oldconnection_list=$this->get_variable('oldconnection_list');
$alert_msg=$this->get_variable('alert_msg');
$aid=$this->get_variable('aid');
$selectall=$this->get_variable('select_all');

?>
<script type="text/javascript">
function CheckOldSelection()
{
	ckdata='<?php echo $oldconnection_list;?>';
	ckdata_arr=ckdata.split('_');

	for(i=0;i< ckdata_arr.length;i++)
    {
		if(ckdata_arr[i] !="")
		{
			if(document.getElementById('connection'+ckdata_arr[i]))
			document.getElementById('connection'+ckdata_arr[i]).checked=true;
		}
    }
}

$(document).ready(function (){
	<?php if($selectall==1){?>
	 $("input:checkbox").prop('checked', true);
	<?php }?>
	$('.connection_div_outer input:checkbox').click(function (){
		$('#select_all:checked').prop('checked', false);
	});
	$('#select_all').click(function (){

		
		if ($('#select_all:checked').length >0){

			 if($('#morediv').css('display')=='block')
				 $("input:checkbox").prop('checked', true);
			 else
			 $(".connection_div_outer input:checkbox").prop('checked', true);
			}
			else $("input:checkbox").prop('checked', false);
		
		});
	
});

</script>

<?php 
if($oldconnection_list =='')
$alert_msg=$this->get_message('all connection targeted');
?>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="checkbox_head"><?php echo $this->get_label('connection targeting');?></div>


<div class="col-md-12 col-sm-12 col-xs-12 box_style" style="margin-top: 10px;">
<div class="col-md-12 col-sm-12 col-xs-12 box_div" style="height: 420px;overflow-y: scroll;">

<div class="col-md-12 col-sm-12 col-xs-12 frame_td_msg1"><?php echo $alert_msg;?></div>




<?php 
$form=$this->create_form();
$form->start("connection_targeting","","post");
?>

<div class="col-md-3 col-sm-6 col-xs-12 "style="padding: 3px;"   >
<input type="checkbox" name="select_all" id="select_all" value="1"  <?php if($selectall==1){?>checked='checked'<?php }?>"><?php echo $this->get_label('select all');?>
</div>

<?php 
$count=count($row);
$row_count=ceil($count/4);

$i=1;
foreach($row as $key=>$value)
{
	//$childcount=CategoryHelper::get_category_child_count($value['id']);
	
	if($i==1){?>
	
	
	<div class="col-md-12 col-sm-12 col-xs-12 connection_div_outer padding-side">

	<?php }?>
	
	
	
	<div class="col-md-3 col-sm-6 col-xs-12 connection_div_inner" style="padding: 3px;">
	<input type="checkbox" name="connection<?php echo $value['id'];?>" id="connection<?php echo $value['id'];?>" value="1" > <?php echo $value['name'];?>
	</div>
	
	<?php if($i % 4 ==0){?>
	</div>
	<?php if($i < $count){?>
	<div class="col-md-12 col-sm-12 col-xs-12 connection_div_outer padding-side" >
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

<?php if($oldconnection_list !=''){?>
<script type="text/javascript">CheckOldSelection();</script>
<?php }?>
</body>
</html>