<?php $this->dispatch("layout/header_iframe", PATH_TO_ROOT);?>

<?php
$row         = $this->get_result('row');
$oldcat_list = $this->get_variable('oldcat_list');
$aid         = $this->get_variable('aid');

$operation_message = $this->get_variable('operation_message');

if($oldcat_list == "")
$operation_message = $this->get_message('all category targeted');

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

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 category-targeting">

<h2 class="section-heading"><?php echo $this->get_label('category targeting');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message"><?php echo $operation_message;?></div>
<?php
$form=$this->create_form();
$form->start("category_targeting","","post");

$count=count($row);

$i                  = 1;
$tempArray          = array();
$tempExclusiveArray = array();

foreach($row as $key=>$value)
{
	if($i == 1){?>

	<div class="row m-0 category_div_outer py-2">

	<?php }?>

	<div class="col-md-3 col-sm-6 col-xs-12 category_div_inner form-check" id="category_div_inner_<?php echo $value['id'];?>">
		<input class="form-check-input" type="checkbox" parentid="<?php echo $value['id'];?>" name="ca<?php echo $value['id'];?>" id="ca<?php echo $value['id'];?>" value="1" />
		<label class="form-check-label"><?php echo $value['name'];?>

		<?php if($displaySitesCount == 1){?>
		<span class="site-count-span">(<?php echo CategoryHelper::get_category_sites_count($value['id']);?>)</span>
		<?php } ?>
		</label>
	<div>
	<?php
		if($value['exclusive_targeting'] == 1)
		{
				$tempExclusiveArray[] = $value['id'];

		}

		$tempArray[] = $value['id'];
	?>


	<?php echo CategoryHelper::get_category_child_list($value['id']);?></div>
	</div>

	<?php if($i % 4 ==0){?>
	</div>
	<?php if($i < $count){?>
	<div class="row m-0 category_div_outer border-seperation py-2">
	<?php }}
	if($i == $count){?>
	</div>
	<?php }?>
	<?php
	$i=$i+1;
}
?>

<div class="form-group col-md-12 col-sm-12 col-xs-12 text-center">
	<input type="hidden" name="tempExclusiveJSON" id="tempExclusiveJSON" value='<?php echo json_encode($tempExclusiveArray);?>' />
	<input type="hidden" name="tempJSON" id="tempJSON" value='<?php echo json_encode($tempArray);?>' />
	<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />

	<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('submit'); ?>" />
</div>

<?php $form->end(); ?>

</div>

</div>
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
<?php $this->dispatch("layout/footer_iframe", PATH_TO_ROOT);?>
