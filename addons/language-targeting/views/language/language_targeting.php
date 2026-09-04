<?php $this->dispatch("layout/header_iframe", PATH_TO_ROOT);?>

<?php
$row               = $this->get_result('row');
$oldlanguage_list  = $this->get_variable('oldlanguage_list');
$aid               = $this->get_variable('aid');
$selectall         = $this->get_variable('select_all');

$operation_message = $this->get_variable('operation_message');

if($oldlanguage_list == "")
$operation_message = $this->get_label('all language targeted');
?>
<script type="text/javascript">
function CheckOldSelection()
{
		ckdata     = '<?php echo $oldlanguage_list;?>';
		ckdata_arr = ckdata.split('_');

		for(i=0; i< ckdata_arr.length; i++)
	  {
				if(ckdata_arr[i] != "")
				{
						if(document.getElementById('language'+ckdata_arr[i]))
						document.getElementById('language'+ckdata_arr[i]).checked = true;
				}
	  }
}

$(document).ready(function ()
{
		$(".language-checkbox").click(function ()
		{
		    if($('.language-checkbox:checked').length == $('.language-checkbox').length)
				$('#select_all').prop('checked', true);
				else
				$('#select_all').prop('checked', false);
		});

		$('#select_all').click(function ()
		{
				if($('#select_all:checked').length > 0)
				$(".language-checkbox").prop('checked', true);
				else
				$(".language-checkbox").prop('checked', false);
		});

		<?php if($selectall == 1){?>
	    $(".language-checkbox").prop('checked', true);
		<?php }?>

		<?php if($oldlanguage_list != ""){?>
				CheckOldSelection();
		<?php }?>
});
</script>



<style type="text/css">

.body-section {
    background-color: #ffffff;
}

</style>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 language-targeting">

<h2 class="section-heading"><?php echo $this->get_label('language targeting');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message"><?php echo $operation_message;?></div>

<?php
$form=$this->create_form();
$form->start("language_targeting","","post");
?>
	<div class="row m-0">

		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-check">
			<input type="checkbox" class="form-check-input mt-1" name="select_all" id="select_all" value="1" <?php if($selectall == 1){?>checked='checked'<?php }?> />
			<label class="form-check-label"><?php echo $this->get_label('select all');?></label>
		</div>

		<?php foreach($row as $key=>$value){?>
			<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6 form-check">
				<input type="checkbox" class="form-check-input mt-1 language-checkbox" name="language<?php echo $value['id'];?>" id="language<?php echo $value['id'];?>" value="1" />
				<label class="form-check-label"><?php echo $value['name'];?></label>
			</div>
		<?php }?>


		<div class="form-group col-md-12 col-sm-12 col-xs-12 text-center">
			<input type="hidden" name="aid" id="aid" value="<?php echo $aid;?>" />
			<input class="submit-button" type="submit" name="submit" value="<?php echo $this->get_label('update');?>" />
		</div>

	</div>
<?php $form->end(); ?>

</div>
<?php $this->dispatch("layout/footer_iframe", PATH_TO_ROOT);?>
