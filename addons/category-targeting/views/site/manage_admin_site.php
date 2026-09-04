
<div class="sub_menu_main"><?php echo $this->get_label('manage sites');?></div>

<?php $this->dispatch("links/links/32");?>

<?php
$owner=$this->get_variable('owner');
$search_by=$this->get_variable('search_by');
$search_text=$this->get_variable('search_text');
$category=$this->get_variable('category');
$status=$this->get_variable('status');
$featured=$this->get_variable('featured');
$hot=$this->get_variable('hot');
$page=$this->get_variable('page');

$data=$this->get_result('data');


$sponsored_enabled = $this->get_addon_status('sponsored_enabled');
$cpp_enabled = $this->get_addon_status('cpp_enabled');

$form=$this->create_form();
$form->start("managesites",'',"post");
?>
<div class="search_div">
<table class="search_div_table">
  <tr>
  <td style="width: 150px;">

    <select name="owner" id="owner" style="width: 140px;">
    <option value="-1" <?php if($owner ==-1){ echo "selected";} ?>><?php echo $this->get_label('all users');?></option>
    <option value="0" <?php if($owner ==0){ echo "selected";} ?>><?php echo $this->get_label('admin');?></option>

    <?php foreach ($data as $key=>$value){?>
    <option value="<?php echo $value['id'];?>" <?php if($owner ==$value['id']){ echo "selected";} ?>><?php echo $value['username'];?></option>
    <?php }?>
    </select>
    </td>
    <td style="width: 150px;">
    <select name="category" id="category" style="width: 140px;">
  	<option value=""><?php echo $this->get_label('select category');?></option>
  	<?php echo CategoryHelper::get_category_dropdown(0,0,$category);?>
  	</select>
    </td>
    <td style="width: 80px;">
    <select name="status" id="status" style="width: 80px;">
    <option value="-2" <?php if($status ==-2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>

    </td>
     <?php if($sponsored_enabled == 1){?>
     <td style="width: 80px;">
    <select name="featured" id="featured" style="width: 80px;">
    <option value="-1" <?php if($featured ==-1) echo "selected"; ?>><?php echo $this->get_label('feature status');?></option>
    <option value="1" <?php if($featured==1) echo "selected"; ?>><?php echo $this->get_label('yes');?></option>
    <option value="0" <?php if($featured==0) echo "selected"; ?>><?php echo $this->get_label('no');?></option>
    </select>

    </td>

    <td style="width: 80px;">
    <select name="hot" id="hot" style="width: 80px;">
    <option value="-1" <?php if($hot ==-1) echo "selected"; ?>><?php echo $this->get_label('hot status');?></option>
    <option value="1" <?php if($hot==1) echo "selected"; ?>><?php echo $this->get_label('yes');?></option>
    <option value="0" <?php if($hot==0) echo "selected"; ?>><?php echo $this->get_label('no');?></option>
    </select>

    </td>
    <?php }?>

	<td style="width: 300px;">
    <select name="search_by" id="search_by" style="width: 100px;">
    <option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
    <option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('site id');?></option>
    <option value="2" <?php if($search_by==2) echo "selected"; ?>><?php echo $this->get_label('site name');?></option>
    <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('owner');?></option>
    </select>
    &nbsp;
    <input type="text" name="search_text" id="search_text" style="width: 140px;" value="<?php echo $search_text;?>"/>
    </td>
    <td>
    <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
    </td>
    </tr>
  </table>
  </div>
 <?php $form->end(); ?>


<br/>

<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">
  <tr class="row_heading_tr">
  <td width="5%"><?php echo $this->get_label('id'); ?></td>
  <td width="25%"><?php echo $this->get_label('site details'); ?></td>
  <td width="30%"><?php echo $this->get_label('category'); ?></td>
   <?php if($cpp_enabled == 1){?>
  <td><?php echo $this->get_label('no of subscribers'); ?></td>
  <?php } ?>
  <td ><?php echo $this->get_label('actions'); ?></td>
  </tr>
<?php
$res=$this->get_result('res');
foreach($res as $key=>$row)
{
	?>
    <tr class="row_data_tr">
    <td><?php echo $row['id'];?></td>
    <td>
    <div style="float: left;display: flex;align-items: center;margin: 4px 0px;">
    <?php if($row['logo'] != ""){?>
    <img src="<?php echo BASE.DATA_DIR."/site_logo/".$row['id']."/".$row['logo'];?>" />
    <?php }else{?>
    <img src="<?php echo BASE.DATA_DIR."/site_logo/".Configuration::get_instance()->read('default_site_logo');?>" />
    <?php }?>
    </div>

    <div style="float: left;padding: 5px;">
    <div style="height: 20px;"><?php echo $this->get_label('site name'); ?> : <?php echo $row['url'];?></div>

    <div style="height: 20px;">
    <?php echo $this->get_label('owner'); ?> : <?php
    if($row['pid'] >0) {?>
    <a href="<?php echo $this->make_url("user/profile/".$row['pid'],ADMIN_DIR);?>"><?php echo $this->escape(CategoryHelper::get_site_owner($row['pid']));?></a>
    <?php } else
    echo $this->get_label('admin');
    ?>
    </div>

    <div style="height: 20px;">
    <?php echo $this->get_label('status'); ?> :
    <span class="<?php if($row['status'] ==0){ ?>blk<?php }
    else if($row['status'] ==-1){ ?>pend<?php }
    else if($row['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($row['status']);?></span>
    </div>


    <?php if($sponsored_enabled == 1){?>
    <div style="height: 20px;">
    <?php echo $this->get_label('marketplace'); ?> :
    <?php
    if($row['marketplace_display'] == 1)
    echo $this->get_label('yes');
    else
    echo $this->get_label('no');
    ?>
    </div>
    <?php }?>

    </div>
    </td>

    <td><?php echo CategoryHelper::get_category_list($row['catid']);?></td>

    <?php if($cpp_enabled == 1){?>
    <td>
      <?php
        if($row['push_notification_service_enabled'] == 1)
        {
             echo $row['active_subscribers_count'];

             if(isset($row['push_api_error_msg']) && $row['push_api_error_msg'] != "")
             echo " - ".$this->get_label('api error -').$row['push_api_error_msg'];
        }
        else
        echo $this->get_label('na');
     ?>
    </td>
    <?php }?>
    <td>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/7/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit'); ?>"></i></a>

    <?php if($row['status'] ==-1){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/9/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate'); ?>"></i></a>

    <a href="<?php echo $this->make_url("dispatch/category_targeting/10/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block'); ?>"></i></a>

    <?php }else if($row['status'] ==1){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/10/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block'); ?>"></i></a>


    <?php if($row['pid'] >0) {?>
    <a href="<?php echo $this->make_url("adunit/manage_user_adcode/".$row['pid']."/-1",ADMIN_DIR);?>"><i class="fa fa fa-file-text-o edit-icon" title="<?php echo $this->get_label('adunits'); ?>"></i></a>
    <?php }else{?>
    <a href="<?php echo $this->make_url("adunit/manage/-1/".$row['id'],ADMIN_DIR);?>"><i class="fa fa fa-file-text-o edit-icon" title="<?php echo $this->get_label('adunits'); ?>"></i></a>
    <?php }?>


    <?php }else if($row['status'] ==0){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/9/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate'); ?>"></i></a>


    <?php if($row['pid'] >0) {?>
    <a href="<?php echo $this->make_url("adunit/manage_user_adcode/".$row['pid']."/-1",ADMIN_DIR);?>"><i class="fa fa fa-file-text-o edit-icon" title="<?php echo $this->get_label('adunits'); ?>"></i></a>
    <?php }else{?>
    <a href="<?php echo $this->make_url("adunit/manage/-1/".$row['id'],ADMIN_DIR);?>"><i class="fa fa fa-file-text-o edit-icon" title="<?php echo $this->get_label('adunits'); ?>"></i></a>
    <?php }?>
    <?php }?>


    <?php if($sponsored_enabled == 1){?>
    <?php if($row['featured'] == 0){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/14/".$row['id']."/1/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('make featured notification');?>');"><i class="fa fa-thumbs-o-up approve-icon" title="<?php echo $this->get_label('make featured'); ?>"></i></a>
    <?php } else { ?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/14/".$row['id']."/0/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('remove featured notification');?>');"><i class="fa fa-thumbs-o-down block-icon" title="<?php echo $this->get_label('remove featured'); ?>"></i></a>
    <?php }  ?>


    <?php if($row['hot'] == 0){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/15/".$row['id']."/1/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('make hot notification');?>');"><i class="fa fa-fire approve-icon" title="<?php echo $this->get_label('make hot'); ?>"></i></a>
    <?php } else { ?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/15/".$row['id']."/0/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('remove hot notification');?>');"><i class="fa fa-fire block-icon" title="<?php echo $this->get_label('remove hot'); ?>"></i></a>
    <?php } ?>
    <?php } ?>

    <a href="<?php echo $this->make_url("dispatch/category_targeting/8/".$row['id']."/".$owner."/".$category."/".$status."/".$featured."/".$hot."/".$search_by."/".$search_text."/".$page,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('site delete message');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete'); ?>"></i></a>
  </td>
  </tr>
<?php
}
?>
<?php if(count($res)==0) {?>
  <tr class="row_data_tr"><td colspan="5"><?php echo $this->get_label('no records found'); ?></td></tr>
<?php }else{ ?>
<tr><td colspan="5" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>
