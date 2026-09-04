<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" href="<?php echo BASE.ADMIN_DIR."/css/style.css";?>" type="text/css" />
<script type='text/javascript' src='<?php echo COMMON_DIR_PATH;?>js/jquery.min.js'></script>
</head>
<body>
<?php
$row=$this->get_result('row');
$oldcat_list=$this->get_variable('oldcat_list');
$alert_msg=$this->get_variable('alert_msg');
$aid=$this->get_variable('aid');

$displaySitesCount = intval(Configuration::get_instance()->read("display_active_site_count_with_category"));
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

$i                  = 1;
$tempArray          = array();
$tempExclusiveArray = array();

foreach($row as $key=>$value)
{
	if($i==1){?>
	<div class="category_div_outer">
	<?php }?>

	<div class="category_div_inner" id="category_div_inner_<?php echo $value['id'];?>">
	<input type="checkbox" parentid="<?php echo $value['id'];?>" name="ca<?php echo $value['id'];?>" id="ca<?php echo $value['id'];?>" value="1" /><?php echo $value['name'];?>

	<?php if($displaySitesCount == 1){?>
	<span style="color: green;">(<?php echo CategoryHelper::get_category_sites_count($value['id']);?>)</span>
	<?php } ?>

	<?php
		if($value['exclusive_targeting'] == 1)
		{
				$tempExclusiveArray[] = $value['id'];


		}

		$tempArray[] = $value['id'];
	?>

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
<input type="hidden" name="tempExclusiveJSON" id="tempExclusiveJSON" value='<?php echo json_encode($tempExclusiveArray);?>' />
<input type="hidden" name="tempJSON" id="tempJSON" value='<?php echo json_encode($tempArray);?>' />

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
<script type="text/javascript">
$(document).ready(function() {

<?php if($oldcat_list != ''){?>
	CheckOldSelection();
<?php }?>

	$(function () {
			$(".category_div_inner input[type='checkbox']").change(function ()
			{
					$(this).siblings('div')
					.find("input[type='checkbox']")
					.prop('checked', this.checked);


					var tempExclusiveArray = JSON.parse($("#tempExclusiveJSON").val());
					var tempArray          = JSON.parse($("#tempJSON").val());

					var currentClickedParentID = $(this).attr("parentid");

					if(tempExclusiveArray.indexOf(currentClickedParentID) > -1)
					{
							if($(this).prop("checked"))
							{
									for(i = 0;i < tempArray.length;i++)
									{
											if(currentClickedParentID != tempArray[i])
											{
														$("#ca"+tempArray[i]).prop('checked', false);
														$("#ca"+tempArray[i]).prop('disabled', true);

														$("#category_div_inner_"+tempArray[i]+" div").siblings('div')
														.find("input[type='checkbox']")
														.prop('checked', false);

														$("#category_div_inner_"+tempArray[i]+" div").siblings('div')
														.find("input[type='checkbox']")
														.prop('disabled', true);
											}
									}
							}
							else
							{
									var isCheckedFlag = 0;

									for(i = 0;i < tempExclusiveArray.length;i++)
									{
											if($("#ca"+tempExclusiveArray[i]).prop('checked') || $("#category_div_inner_"+tempExclusiveArray[i]+" div").siblings('div')
											.find("input[type='checkbox']")
											.prop('checked'))
											{
													isCheckedFlag = 1;
											}
									}

									if(isCheckedFlag == 0)
									{
											for(i = 0;i < tempArray.length;i++)
											{
													$("#ca"+tempArray[i]).prop('disabled', false);

													$("#category_div_inner_"+tempArray[i]+" div").siblings('div')
													.find("input[type='checkbox']")
													.prop('disabled', false);
											}
									}
							}
					}
			});
	});


	var tempExclusiveArray = JSON.parse($("#tempExclusiveJSON").val());
	var tempArray          = JSON.parse($("#tempJSON").val());

	var isCheckedFlag   = 0;
	var checkedCategory = 0;

	for(i = 0;i < tempExclusiveArray.length;i++)
	{
			if($("#ca"+tempExclusiveArray[i]).prop('checked') || $("#category_div_inner_"+tempExclusiveArray[i]+" div").siblings('div')
			.find("input[type='checkbox']")
			.prop('checked'))
			{
					isCheckedFlag   = 1;
					checkedCategory = tempExclusiveArray[i];
			}
	}

	if(isCheckedFlag == 1 && checkedCategory > 0)
	{
			for(i = 0;i < tempArray.length;i++)
			{
					if(checkedCategory != tempArray[i])
					{
								$("#ca"+tempArray[i]).prop('checked', false);
								$("#ca"+tempArray[i]).prop('disabled', true);

								$("#category_div_inner_"+tempArray[i]+" div").siblings('div')
								.find("input[type='checkbox']")
								.prop('checked', false);

								$("#category_div_inner_"+tempArray[i]+" div").siblings('div')
								.find("input[type='checkbox']")
								.prop('disabled', true);
					}
			}
	}

});
</script>
</body>
</html>
