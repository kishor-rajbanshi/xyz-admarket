<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');

	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>

<?php
$category=$this->get_variable('category');
$status=$this->get_variable('status');
$siteID=$this->get_variable('siteID');
$page=$this->get_variable('page');

$sponsored_enabled = $this->get_addon_status('sponsored_enabled');
$cpp_enabled = $this->get_addon_status('cpp_enabled');
$enable_website_ownership_verification = Configuration::get_instance()->read('enable_website_ownership_verification');
?>


<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 p-3 mb-3 manage-site-list table-outer-box">
	<h2 class="page-heading page-heading-flex"><div class="page-inner"><i class="fa fa-list icon_red"></i><?php echo $this->get_label('manage sites');?></div>

<a class="create-btn" href="<?php echo $this->make_base_url("dispatch/category_targeting/5");?>">
    <p><i class="fa fa-plus-circle" aria-hidden="true"></i><?php echo $this->get_label('add site');?></p>
</a></h2>


<?php
$form=$this->create_form();
$form->start("managesites",$this->make_base_url("dispatch/category_targeting/6"),"post");
?>
	<div class="row mb-3 px-0 search_div">

			 <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1">
					 <select class="form-select" aria-label="category" name="category" id="category">
						 <bdi><option value=""><?php echo $this->get_label('select category');?></option>
						 <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?></bdi>
					 </select>
			 </div>

			 <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 mb-1">
				 <select class="form-select" aria-label="status" name="status" id="status">
					 <option value="-2" <?php if($status ==-2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
					 <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
					 <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
					 <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
					 <option value="-3" <?php if($status==-3) echo "selected"; ?>><?php echo $this->get_label('draft');?></option>
					</select>
			 </div>

			 <div class="col-auto">
			 		<input class="btn btn-info" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
			 </div>
	</div>
<?php $form->end();?>


<!-- Starting of Verification Pop Up -->
<?php if($enable_website_ownership_verification == 1){

	$siteKey  = "";
	$siteName = "";

	if($siteID > 0)
	{
			$siteKey  = CategoryHelper::get_site_verification_key($siteID);
			$siteName = CategoryHelper::get_site_name($siteID);
	}
	?>
	<div class="modal fade" id="GetVerificationCode" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
	    	<div class="modal-content">
	      		<div class="modal-header">
	        		<h4 class="modal-title"><bdi><?php echo $this->get_label('verify your website');?> - <span id="verify-site"><?php echo $siteName;?></span></bdi></h4>
	        		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
	      		</div>

	      		<div class="modal-body">
						 	<div class="tab-content">
						    	<div role="tabpanel" class="tab-pane active">
											<div><?php echo $this->get_label("upload file to the root");?></div>
											<div>
												<button class="submit-button" name="download-button" onclick="DownloadFile();"><?php echo $this->get_label("download file");?></button>
												<span id="verify-file"><?php echo $siteKey.".html";?></span>
											</div>
											<div><?php echo $this->get_label("or");?></div>
											<div><?php echo $this->get_label("pleace tag inside head");?></div>

										  <textarea class="form-control" id="verify-text" rows="2"><meta name="admverifysite" content="<?php echo $siteKey; ?>" /></textarea>

											<input type="hidden" id="verify-sid" value="<?php echo $siteID;?>" />

											<div >
													<button class="submit-button" name="verify-button" onclick="VerifySite();"><?php echo $this->get_label("verify");?></button>
										  </div>
											<div id="verification-loading" style="display: none;"><img src="<?php echo BASE;?>images/load.gif"/></div>
											<div id="verification-message" class="notification mb-2" style="display: none;"></div>
											<div class="notification"><?php echo $this->get_label("verification note");?></div>
						    	</div>
						</div>
		      	</div>
	    	</div>
		 </div>
	</div>
<?php } ?>
<!-- End of Verification Pop up -->

<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
  <tr class="data_table_head">
	<td><?php echo $this->get_label('site info'); ?></td>
	<td><?php echo $this->get_label('category'); ?></td>
	<td><?php echo $this->get_label('status'); ?></td>

	<?php if($sponsored_enabled == 1){?>
	<td><?php echo $this->get_label('marketplace'); ?></td>
	<?php }?>

	<?php if($cpp_enabled == 1){?>
	<td> <?php echo $this->get_label('no of subscribers'); ?>  </td>
	<?php } ?>
	<td><?php echo $this->get_label('actions'); ?></td>
  </tr>
<?php
$res = $this->get_result('res');

if(count($res)==0){?>
	<tr class="data_table_content">
		<td colspan="7"><?php echo $this->get_label('no records found'); ?></td>
	</tr>
<?php } else {

		foreach($res as $key => $row)
		{
			 $metaString = '<meta name="admverifysite" content="'.$row['verification_key'].'" />';
			 ?>
		    <tr class="data_table_content">
				<td><bdi><?php echo $row['id']." - ".$row['url'];?></bdi></td>
				<td>
				<button
						class="btn btn-info site-categories-class"
						data-bs-toggle="popover"
						data-bs-placement="bottom"
						data-bs-content="<?php echo CategoryHelper::get_category_list($row['catid']);?>"
						>
					 <i class="fa fa-th" aria-hidden="true"></i>
				 </button>

				</td>
				<td><?php echo $this->get_ad_status($row['status']);?></td>

		    <?php if($sponsored_enabled == 1){?>
		    <td>
			    <?php
			    if($row['marketplace_display'] == 1)
			    echo $this->get_label('yes');
			    else
			    echo $this->get_label('no');
			    ?>
		    </td>
		    <?php }?>

		     <?php if($cpp_enabled == 1){?>
		     <td>
					 <bdi>
							 <?php
							 if($row['push_notification_service_enabled'] == 1)
						   {
								 	echo $row['active_subscribers_count'];

									if(isset($row['push_api_error_msg']) && $row['push_api_error_msg'] != "")
									echo " - ".$this->get_label('api error')." - ".$row['push_api_error_msg'];
							 }
							 else
							 echo $this->get_label('na');
							 ?>
				 	 </bdi>
				</td>
		    <?php }?>
		    <td>
			<?php if($row['status'] == -3 && $enable_website_ownership_verification == 1){?>
				<input type="hidden" id="verification-meta-<?php echo $row['id'];?>" value='<?php echo $metaString;?>' />
				<input type="hidden" id="verification-site-<?php echo $row['id'];?>" value='<?php echo $row['protocol'].$row['url'];?>' />
				<input type="hidden" id="verification-file-<?php echo $row['id'];?>" value='<?php echo $row['verification_key'].".html";?>' />

				<a id="verification-button-<?php echo $row['id'];?>" data-bs-toggle="modal" data-bs-target="#GetVerificationCode" data-checkid="<?php echo $row['id'];?>">
					<i class="fa fa-check verify-icon" title="<?php echo $this->get_label('verify');?>"></i>
				</a>
			  <?php } ?>

				<?php //if($row['status'] != 0){ //Blocked sites don't edit option ?>
		    <a href="<?php echo $this->make_url("dispatch/category_targeting/7/".$row['id']."/".$category."/".$status."/".$page,BASE);?>">
					<i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i>
				</a>
				<?php //}?>

				<a href="<?php echo $this->make_url("adunit/view/-1/".$row['id'],BASE);?>">
					<i class="fa fa-file-code-o adcode-icon" title="<?php echo $this->get_label('manage adunits'); ?>"></i>
				</a>

				<?php //if($row['status'] != 0){ //Blocked sites don't delete option ?>
		    <a href="<?php echo $this->make_url("dispatch/category_targeting/8/".$row['id']."/".$category."/".$status."/".$page,BASE);?>" onclick="return confirm('<?php echo $this->get_message('site delete message');?>');">
					<i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i>
				</a>
				<?php //}?>
			</td>
		  </tr>
		<?php
		}
}
?>
</table>

<div class="row">
	<div class="col-lg-12 col-sm-12 col-md-12 col-xs-12 mt-3 d-flex flex-row-reverse">
		<?php echo $this->get_variable('pagination');?>
	</div>
</div>

</div>


<script type="text/javascript">
$(document).ready(function()
{
		var options = {
						html    : true,
						title   : "<?php echo $this->get_label("site categories");?>",
						trigger : 'focus',
				}

		var itemsLength = $(".site-categories-class").length;

		for(i = 0; i < itemsLength; i++)
		{
				var buttonElement = document.getElementsByClassName("site-categories-class")[i];
				var popover       = new bootstrap.Popover(buttonElement, options);
		}

		$(window).resize(function()
		{
				var itemsLength = $(".site-categories-class").length;

				for(i = 0; i < itemsLength; i++)
				{
						var buttonElement = document.getElementsByClassName("site-categories-class")[i];
						var popover       = new bootstrap.Popover(buttonElement, options);
				}
		});
});


<?php if($enable_website_ownership_verification == 1){?>
function DownloadFile()
{
		window.location.href = "<?php echo $this->make_base_url("site/verification_file/",PATH_TO_ROOT.ADDON_DIR.'/category-targeting');?>"+$('#verify-sid').val();
}

function VerifySite()
{
		siteID = $('#verify-sid').val();

		$("#verification-loading").show();

		dataparam = "siteID="+siteID;

		var urlvalue = '<?php echo $this->make_base_url("site/verify_site_user",PATH_TO_ROOT.ADDON_DIR.'/category-targeting');?>';

		$.ajax(
		{
			type: "POST",
			data: dataparam,
			url: urlvalue,
			success: function(msg)
			{
					message = "";

					if(msg == 0)
					message="<?php echo $this->get_message('invalid operation');?>";
					else if(msg == 1)
					message="<?php echo $this->get_message('site verification failed');?>";
					else if(msg == 2)
					message="<?php echo $this->get_message('successfully verified the site');?>";

					$('#verification-message').html(message);

					if(msg != 2)
					$('#verification-message').css('color','red');
					else
					$('#verification-message').css('color','green');

					$('#verification-message').show();

					$("#verification-loading").hide();

					setTimeout(function(){
							$('#verification-message').slideUp();

							if(msg == 2)
							window.location.href="<?php echo $this->make_base_url("dispatch/category_targeting/6/".$category."/-2/0/".$page);?>";

					},1500);
			}
		});
}


$(document).ready(function()
{
	<?php if($siteID > 0){?>
  $('#verification-button-<?php echo $siteID;?>').click();
  <?php } ?>

	$('#GetVerificationCode').on('show.bs.modal', function (event) {

		var modal = $(this);
        var button = $(event.relatedTarget); // btn that triggered the modal
        var id = button.attr('data-checkid');

		$('#verify-text').html($('#verification-meta-'+id).val());
		$('#verify-site').html($('#verification-site-'+id).val());
		$('#verify-file').html($('#verification-file-'+id).val());

		$('#verify-sid').val(id);

	});
});
<?php } ?>
</script>
