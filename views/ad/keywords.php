<?php $this->dispatch("layout/header_iframe");?>

<?php
$aid=$this->get_variable('aid');
$uid=$this->get_variable('uid');

$res=$this->get_result('res');
$value=$res[0];

$bannerid=$value['banner_id'];
$adtype=$value['type'];

$deviceenabled=$this->get_addon_status('device-targeting_enabled');
if($deviceenabled ==1)
$device=$value['device'];
else
$device=0;

$operation_message = $this->get_variable("operation_message");
$action            = $this->get_variable("action");
?>

<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 keyword-targeting">

<h2 class="section-heading"><?php echo $this->get_label('keyword targeting');?></h2>

<div class="col-md-12 col-sm-12 col-xs-12 operation-status-message">
	<bdi><?php echo $operation_message;?></bdi>
</div>


<?php if(Configuration::get_instance()->read('keyword_based_ad_display') == 1){?>

	<div class="col-md-12 col-sm-12 col-xs-12">
		<?php
		$form=$this->create_form();
		$form->start("keywords",$this->make_url("ad/keywords/".$aid),"post");
		?>
			<div class="col-md-5 col-sm-5 col-xs-12">
				<div class="mb-3">
			      <label class="form-label"><?php echo $this->get_label('keywords');?></label>
			  		<textarea class="form-control" name="keywords" rows="5"></textarea>
				  	<div class="notification">[<?php echo $this->get_label('seperated by');?>]</div>
			  </div>

				<div class="mb-3">
			  		<input type="hidden" name="uid" value="<?php echo $uid;?>" />
			      <input type="hidden" name="aid" value="<?php echo $aid;?>" />
			      <input class="submit-button" type="submit" name="keysubmit" value="<?php echo $this->get_label('add keywords');?>" />
			  </div>
			</div>
		<?php $form->end(); ?>
	</div>

<?php }?>

<div class="col-md-12 col-sm-12 col-xs-12">
	<h2 class="section-heading mb-3"><?php echo $this->get_label('manage targeted keywords');?></h2>

		<div id="keyword-deletion-message" class="operation-status-message">
			<?php if($action == 1){ echo $this->get_message("keyword deleted"); }?>
		</div>

		<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
			<tr class="data_table_head">
				<td style="width: 250px;"><?php echo $this->get_label('keywords');?></td>
				<td><?php echo $this->get_label('action');?></td>
			</tr>

		<?php
		$result = $this->get_result('result');
		$pg     = $this->get_variable("pg");

		if(count($result) == 0){?>
		<tr class="data_table_content">
			<td colspan="4">
				<?php echo $this->get_label('global targeted');?>
			</td>
		</tr>
		<?php } else {

			foreach($result as $key => $value1)
			{
					$kid = $value1['mid'];
					?>

					<tr class="data_table_content">
						<td>
							<input  type="text" class="form-control" name="keyword<?php echo $kid;?>" id="keyword<?php echo $kid;?>" value="<?php echo $value1['keyword'];?>" readonly>
							<input type="hidden" name="keyword_dummy<?php echo $kid;?>" id="keyword_dummy<?php echo $kid;?>" value="<?php echo $value1['keyword']; ?>">
						</td>
						<td>
							<input class="submit-button my-1" type="button" id="edit<?php echo $kid;?>" name="edit<?php echo $kid;?>" value="<?php echo $this->get_label('edit');?>" onClick="editKeyword(<?php echo $kid;?>)" />
							<input class="submit-button my-1" style="display: none;" type="button" id="cancel<?php echo $kid;?>" name="cancel<?php echo $kid;?>" value="<?php echo $this->get_label('cancel');?>" onClick="cancelKeyword(<?php echo $kid;?>)" />

							<a class="submit-button my-1" href="<?php echo $this->make_url("ad/delete_keyword/".$kid."/".$aid."/".$pg);?>" onClick="return confirm('<?php echo $this->get_message('do you really want to delete this keyword');?>')"><?php echo $this->get_label('delete');?></a>

							<span id="img<?php echo $kid;?>" style="display: none;"><img  src="images/load.gif" /></span>
						</td>
					</tr>

		<?php }
			}
		?>
		</table>

		<input type="hidden" name="aid" id="aid" value="<?php echo $aid; ?>" />

		<div class="row">
			<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
				<?php echo $this->get_variable('pagination');?>
			</div>
		</div>

	</div>
</div>

<script language="Javascript" type="text/javascript">
function trim(stringData)
{
return stringData.replace(/(^\s*|\s*$)/, "");
}

function cancelKeyword(kid)
{
		$("#edit"+kid).val("<?php echo $this->get_label('edit');?>");
		$("#cancel"+kid).hide();
		$("#keyword"+kid).val($("#keyword_dummy"+kid).val());
		$("#keyword"+kid).attr('readonly', true);
}

function editKeyword(kid)
{
	$('.operation-status-message').hide();

	if($("#edit"+kid).val() == "<?php echo $this->get_label('edit');?>")
	{
		$("#edit"+kid).val("<?php echo $this->get_label('update');?>");
		$("#cancel"+kid).show();
		$("#keyword"+kid).attr('readonly', false);
		$("#keyword"+kid).focus();
	}
	else if($("#edit"+kid).val() == "<?php echo $this->get_label('update');?>")
	{
		var keyword=trim($("#keyword"+kid).val());
		var aid=$('#aid').val();

		if(keyword == "")
		{
				alert('<?php echo $this->get_message('mandatory'); ?>');
				$("#keyword"+kid).focus();
				return;
		}

		$("#img"+kid).show();

		dataparam = "keyword="+keyword+"&kid="+kid+"&aid="+aid;

		  $.ajax(
				   {
						type: "POST",
						data: dataparam,
						url: "<?php echo $this->make_url("ad/edit_keyword")?>",
						success: function(msg)
						{
							returnmessage=msg;
							returnmessage1=returnmessage.split("_");

							$("#img"+kid).hide();

							if(returnmessage1[0] != 1)
							{
								$('#keyword-deletion-message').html(msg);

								$("#edit"+kid).val("<?php echo $this->get_label('edit');?>");
								$("#cancel"+kid).hide();

								$("#keyword"+kid).attr('readonly', true);

								$("#keyword_dummy"+kid).val(keyword);
							}
							else
							{
								$('#keyword-deletion-message').html(returnmessage1[1]);
							}
						}
					});
	}
}
</script>
<?php $this->dispatch("layout/footer_iframe");?>
