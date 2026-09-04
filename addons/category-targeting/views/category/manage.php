<?php
$tab             = $this->get_variable('tab');

$pid             = $this->get_variable("pid");
$parentExclusive = 0;

if($pid > 0)
$parentExclusive = CategoryHelper::get_parent_category_exclusive($pid);
?>
<script type="text/javascript">
function show_pub_stat(id)
{
	$('.showadustatclass').hide();
	$('.adustatclass').removeClass('tab-selection');

	$('#showstat'+id).show();
	$('#adustat'+id).addClass('tab-selection');

	$('#tab').val(id);

	if(id == 1)
	{
			$('#sub_menu_main_1').show();
			$('#sub_menu_main_2').hide();
			$('#add_parent_category').hide();
	}
	else
	{
			$('#sub_menu_main_1').hide();
			$('#sub_menu_main_2').show();
			$('#add_parent_category').show();
	}
}
</script>


<script type="text/javascript">
function status_change(id)
{
	dataparam = "catID="+id;
	$('#loading_'+id).show();

var urlvalue='<?php echo $this->make_base_url("category/status_change",ADDON_DIR."/category-targeting");?>';
$.ajax(
{
	type: "POST",
	data: dataparam,
	url: urlvalue,
	success: function(message)
	{
			$('#loading_'+id).hide();
	    if(message==3)	//category child exists
		set_jnotice(0,"<?php echo $this->get_message('category child exists iab'); ?>");
			else if(message==1)	//category invalid
			set_jnotice(0,"<?php echo $this->get_message('category invalid'); ?>");
			else if(message==2)	//parent is blocked
			set_jnotice(0,"<?php echo $this->get_message('parent blocked'); ?>");
		else
		{
			if($('#category_action_input_'+id).val()==1) //new status became 0
			{
				$('#category_action_'+id).html("<?php echo $this->get_label('activate');?>");
				$('#category_status_'+id).html("<?php echo $this->get_label('blocked');?>");
							$('#category_status_'+id).css("color","red");
				$('#category_action_input_'+id).val(0);
							set_jnotice(1,"<?php echo $this->get_message('category blocked'); ?>");
			}
			else if ($('#category_action_input_'+id).val()==0) //new satus became 1
			{
				$('#category_action_'+id).html("<?php echo $this->get_label('block');?>");
				$('#category_status_'+id).html("<?php echo $this->get_label('active');?>");
							$('#category_status_'+id).css("color","green");
				$('#category_action_input_'+id).val(1);
							set_jnotice(1,"<?php echo $this->get_message('category activated'); ?>");
			}
		}
	}
});
}

</script>


<div id="sub_menu_main_1" class="sub_menu_main"><?php echo $this->get_label('manage categories');?>
 <a href="<?php echo $this->make_url("dispatch/category_targeting/2/0/1",ADMIN_DIR); ?>"><?php echo $this->get_label('root'); ?></a> &raquo; <?php echo $this->get_variable('categorypath_1'); ?>
</div>

<div id="sub_menu_main_2" class="sub_menu_main"><?php echo $this->get_label('manage categories');?>
 <a href="<?php echo $this->make_url("dispatch/category_targeting/2/0/2",ADMIN_DIR); ?>"><?php echo $this->get_label('root'); ?></a> &raquo; <?php echo $this->get_variable('categorypath_2'); ?>
</div>

<span id="add_parent_category">
<?php $this->dispatch("links/links/30");?>
</span>

<div class="report_div">

<table style="width: 100%;" cellpadding="0" cellspacing="0">

<tr><td colspan="4" >
<table style="width: 100%;" cellpadding="0" cellspacing="0">


  <tr class="statistics_header">
    <td onclick="show_pub_stat(1);" id="adustat1" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('iab categories');?></td>
    <td onclick="show_pub_stat(2);" id="adustat2" class="adustatclass" style="width: 150px;"><?php echo $this->get_label('custom categories');?></td>
    <td></td>
  </tr>


   <tr id="showstat1" class="showadustatclass statistics_tr">
   <td colspan="6" style="padding: 5px;" class="statistics_td">

		<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

		  <tr class="row_heading_tr">
		    <td width="40%"><?php echo $this->get_label('category name'); ?></td>
		    <td width="20%"><?php echo $this->get_label('iab id'); ?></td>
		    <td width="20%"><?php echo $this->get_label('status'); ?></td>
		    <?php if($pid == 0 || $parentExclusive == 1){ ?>
    			<td width="20%"><?php echo $this->get_label('exclusive targeting'); ?></td>
    		    <?php } ?>
		    <td width="20%"><?php echo $this->get_label('action'); ?></td>
		  </tr>

		<?php
		$iab_categories=$this->get_result('iab_categories');
		foreach($iab_categories as $key=>$row)
		{
			$child_count=CategoryHelper::get_category_child_count($row['id']);
			?>
		    <tr class="row_data_tr">
		    <td>
		    <?php if($child_count >0){
		    ?>
		    <a href="<?php echo $this->make_url("dispatch/category_targeting/2/".$row['id']."/1",ADMIN_DIR);?>"><?php echo $row['name']; ?></a> (<?php echo $child_count;?>)
		    <?php } else {   echo $row['name']." (".$child_count.")";  }
		    ?>
		    </td>
		    <td> <?php echo $row['iab_id']; ?></td>
				<?php if($row['category_status']==1) {?>
				<td	id="category_status_<?php echo $row['id']?>" style="color : green;"> <?php echo $this->get_label('active'); ?></td>
			<?php } else if($row['category_status']==0){?>
				<td	id="category_status_<?php echo $row['id']?>" style="color : red;"> <?php echo $this->get_label('blocked'); ?></td>
			<?php } ?>

			   <?php if($pid == 0 || $parentExclusive == 1){ ?>
      <td>
        <?php
        if($row['exclusive_targeting'] == 1 || $parentExclusive == 1)
        echo $this->get_label('yes');
        else if($row['exclusive_targeting'] == 0)
        echo $this->get_label('no');
        ?>
      </td>
			<?php } ?>

		    <td>
		    <button id="category_action_<?php echo $row['id']?>" class="link_button" onclick="status_change(<?php echo $row['id']?>,<?php echo $row['category_status']?>);" value=""><?php if($row['category_status']==1){ echo $this->get_label('block');} else { echo $this->get_label('activate');} ?></button>
				<input type="hidden" id="category_action_input_<?php echo $row['id']?>" value="<?php echo $row['category_status']?>">
				<span><img id="loading_<?php echo $row['id']?>" src="images/load.gif" style="display: none;" /></span>
			</td>
		  </tr>
		<?php
		}
		?>
		<?php if(count($iab_categories)==0) {?>
		  <tr class="row_data_tr"><td colspan="5"><?php echo $this->get_label('no records found'); ?></td></tr>
		<?php } ?>
		</table>
  </td>
  </tr>



  <tr id="showstat2" class="showadustatclass statistics_tr">
  <td colspan="6" style="padding: 5px;" class="statistics_td">
    			<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

				  <tr class="row_heading_tr">
				    <td width="30%"><?php echo $this->get_label('category name'); ?></td>
				    <td width="10%"><?php echo $this->get_label('iab id'); ?></td>
				    <td width="10%"><?php echo $this->get_label('status'); ?></td>

				    <?php if($pid == 0 || $parentExclusive == 1){ ?>
    			<td width="20%"><?php echo $this->get_label('exclusive targeting'); ?></td>
    		    <?php } ?>
				    <td><?php echo $this->get_label('actions'); ?></td>
				  </tr>


				<?php
				$custom_categories=$this->get_result('custom_categories');
				foreach($custom_categories as $key=>$row)
				{
					$child_count=CategoryHelper::get_category_child_count($row['id']);
					?>
				    <tr class="row_data_tr">
				    <td>
				    <?php if($child_count >0){
				    ?>
				    <a href="<?php echo $this->make_url("dispatch/category_targeting/2/".$row['id']."/2",ADMIN_DIR);?>"><?php echo $row['name']; ?></a> (<?php echo $child_count;?>)
				    <?php } else {   echo $row['name']." (".$child_count.")";  }
				    ?>
				    </td>
				    <td>
							<?php
							if($row['iab_id'] != "")
							echo $row['iab_id'];
							else
							echo "-";
							?>
						</td>
						<?php if($row['category_status']==1) {?>
						<td	id="category_status_<?php echo $row['id']?>" style="color : green;"> <?php echo $this->get_label('active'); ?></td>
					  <?php } else if($row['category_status']==0){?>
						<td	id="category_status_<?php echo $row['id']?>" style="color : red;"> <?php echo $this->get_label('blocked'); ?></td>
					  <?php } ?>


				   <?php if($pid == 0 || $parentExclusive == 1){ ?>
				      <td>
				        <?php
				        if($row['exclusive_targeting'] == 1 || $parentExclusive == 1)
				        echo $this->get_label('yes');
				        else if($row['exclusive_targeting'] == 0)
				        echo $this->get_label('no');
				        ?>
				      </td>
					 <?php } ?>

				  <td>
				    <a href="<?php echo $this->make_url("dispatch/category_targeting/1/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('add child'); ?></a>


						<?php if($row['built_in_category'] == 0){?>
						    <a href="<?php echo $this->make_url("dispatch/category_targeting/3/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('edit'); ?></a>
						    <a href="<?php echo $this->make_url("dispatch/category_targeting/4/".$row['id'],ADMIN_DIR);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('category delete message');?>');"><?php echo $this->get_label('delete'); ?></a>
								<button id="category_action_<?php echo $row['id']?>" class="link_button" onclick="status_change(<?php echo $row['id']?>,<?php echo $row['category_status']?>);" value=""><?php if($row['category_status']==1){ echo $this->get_label('block');} else { echo $this->get_label('activate');} ?></button>
								<input type="hidden" id="category_action_input_<?php echo $row['id']?>" value="<?php echo $row['category_status']?>">
								<span><img id="loading_<?php echo $row['id']?>" src="images/load.gif" style="display: none;" /></span>
						<?php } ?>

					</td>

				  </tr>
				<?php
				}
				?>
				<?php if(count($custom_categories)==0) {?>
				  <tr class="row_data_tr"><td colspan="5"><?php echo $this->get_label('no records found'); ?></td></tr>
				<?php } ?>
				</table>

   </td>
  </tr>
</table>

</td></tr>

</table>
</div>

<script type="text/javascript">
show_pub_stat(<?php echo $tab;?>);
</script>
