
<div class="sub_menu_main"><?php echo $this->get_label('manage sites');?></div>

<?php $this->dispatch("links/links/32");?>

<?php 
$owner=$this->get_variable('owner');
$search_by=$this->get_variable('search_by');
$search_text=$this->get_variable('search_text');
$category=$this->get_variable('category');
$status=$this->get_variable('status');
$page=$this->get_variable('page');

$data=$this->get_result('data');

$form=$this->create_form();
$form->start("managesites",'',"post");
?>
<div class="search_div">
<table class="search_div_table">
  <tr>
  <td style="width: 160px;">
    
    <select name="owner" id="owner" style="width: 150px;">
    <option value="-1" <?php if($owner ==-1){ echo "selected";} ?>><?php echo $this->get_label('all users');?></option>
    <option value="0" <?php if($owner ==0){ echo "selected";} ?>><?php echo $this->get_label('admin');?></option>

    <?php foreach ($data as $key=>$value){?>
    <option value="<?php echo $value['id'];?>" <?php if($owner ==$value['id']){ echo "selected";} ?>><?php echo $value['username'];?></option>
    <?php }?>
    </select>
    </td>
    <td style="width: 160px;">    
    <select name="category" id="category" style="width: 150px;">
  	<option value=""><?php echo $this->get_label('select category');?></option>
  	<?php echo CategoryHelper::get_category_dropdown(0,0,$category);?>
  	</select>
    </td>    
    <td style="width: 160px;">
    <select name="status" id="status" style="width: 150px;">
    <option value="-2" <?php if($status ==-2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
    
    </td>
	<td style="width: 450px;">
    <select name="search_by" id="search_by" style="width: 150px;">
    <option value="0" <?php if($search_by ==0) echo "selected"; ?>><?php echo $this->get_label('search by');?></option>
    <option value="1" <?php if($search_by ==1) echo "selected"; ?>><?php echo $this->get_label('site id');?></option>
    <option value="2" <?php if($search_by==2) echo "selected"; ?>><?php echo $this->get_label('site name');?></option>
    <option value="3" <?php if($search_by==3) echo "selected"; ?>><?php echo $this->get_label('owner');?></option>
    </select>
    &nbsp;
    <input type="text" name="search_text" id="search_text" value="<?php echo $search_text;?>"/>
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
  <td width="3%"><?php echo $this->get_label('id'); ?></td>
  <td width="10%"><?php echo $this->get_label('site name'); ?></td>
  <td width="7%"><?php echo $this->get_label('site logo'); ?></td>
  <!-- <td width="8%"><?php echo $this->get_label('alexa rank'); ?></td>-->
  <td width="6%"><?php echo $this->get_label('owner'); ?></td>
  <td width="5%"><?php echo $this->get_label('status'); ?></td>
  <td width="20%"><?php echo $this->get_label('category'); ?></td>
  <td width="30%"><?php echo $this->get_label('actions'); ?></td>
  </tr>
<?php 
$res=$this->get_result('res');
foreach($res as $key=>$row)
{
	?>
    <tr class="row_data_tr">
    <td><?php echo $row['id'];?></td>
    
    <td><?php echo $row['url'];?></td>
    
    
    <td>
    <?php if($row['logo'] !=""){?>
	<img src="<?php echo BASE.DATA_DIR."/site_logo/".$row['id']."/".$row['logo'];?>" style="padding: 5px 0px;" />
	<?php }else{?>
	<img src="<?php echo BASE.DATA_DIR."/site_logo/".Configuration::get_instance()->read('default_site_logo');?>" style="padding: 5px 0px;" />
	<?php }?>
	</td>
    
        <!--
    
    <td><?php echo $row['alexa_rank'];?></td>
    -->
    
    
    <td><?php 
    if($row['pid'] >0) {?>   
    <a href="<?php echo $this->make_url("user/profile/".$row['pid'],ADMIN_DIR);?>"><?php echo $this->escape(CategoryHelper::get_site_owner($row['pid']));?></a>
    <?php } else 
    echo $this->get_label('admin');	
    ?></td>
    <td class="<?php if($row['status'] ==0){ ?>blk<?php }
    else if($row['status'] ==-1){ ?>pend<?php }
	else if($row['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($row['status']);?></td>
    <td><?php echo CategoryHelper::get_category_path($row['catid']);?></td>
    <td>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/7/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('edit'); ?></a>
    
    <?php if($row['status'] ==-1){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/9/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('activate'); ?></a>
    
    <a href="<?php echo $this->make_url("dispatch/category_targeting/10/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('block'); ?></a>
 
    <?php }else if($row['status'] ==1){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/10/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('block'); ?></a>
    
    
    <?php if($row['pid'] >0) {?>   
    <a href="<?php echo $this->make_url("adunit/manage_user_adcode/".$row['pid']."/-1",ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('adunits'); ?></a>
    <?php }else{?>
    <a href="<?php echo $this->make_url("adunit/manage/-1/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('adunits'); ?></a>
    <?php }?>
    
    
    <?php }else if($row['status'] ==0){?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/9/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('activate'); ?></a>
    
    
    <?php if($row['pid'] >0) {?>   
    <a href="<?php echo $this->make_url("adunit/manage_user_adcode/".$row['pid']."/-1",ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('adunits'); ?></a>
    <?php }else{?>
    <a href="<?php echo $this->make_url("adunit/manage/-1/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('adunits'); ?></a>
    <?php }?>
    
    
    
    <?php }?>

    
    <a href="<?php echo $this->make_url("dispatch/category_targeting/8/".$row['id']."/".$owner."/".$category."/".$status."/".$page."/".$search_by."/".$search_text,ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('site delete message');?>');" class="link_button"><?php echo $this->get_label('delete'); ?></a> </td>
  </tr>
<?php 
} 
?>
<?php if(count($res)==0) {?>
  <tr class="row_data_tr"><td colspan="10"><?php echo $this->get_label('no records found'); ?></td></tr>
<?php }else{ ?>
<tr><td colspan="10" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>