<script type="text/javascript">
$(document).ready(function() {
	CreateResponsiveTable('table-desktop');
	
	$(window).resize(function()
	{
	 	CreateResponsiveTable('table-desktop');
	});
});
</script>


<div class="container"><h2 class="page_heading"><?php echo $this->get_label('manage sites');?></h2></div>

<div class="container">	

<?php 
$category=$this->get_variable('category');
$status=$this->get_variable('status');
$page=$this->get_variable('page');

$form=$this->create_form();
$form->start("managesites",'',"post");
?>
<div class="search_div" style="float: left;width: 100%;">
  
 <div class="form-group search_div_items" style="width: 180px;">
 <select class="form-control" name="category" id="category" style="width: 150px;">
 <bdi><option value=""><?php echo $this->get_label('select category');?></option>
 <?php echo CategoryHelper::get_category_dropdown(0,0,$category);?></bdi>
 </select>
 </div>
    
 <div class="form-group search_div_items" style="width: 150px;">
 <select class="form-control" name="status" id="status" style="width: 150px;">
 <option value="-2" <?php if($status ==-2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
 <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
 <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
 <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
 </select>
 </div>
    
 <div class="form-group search_div_items">
 <input class="btn btn-danger" type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
 </div>
</div>
<?php $form->end(); ?> 


<table id="table-desktop" class="data_table" cellpadding="0" cellspacing="0">
  <tr class="data_table_head">
  <td width="20%"><?php echo $this->get_label('site name'); ?></td>
  <td width="10%"><?php echo $this->get_label('status'); ?></td>
  <td width="30%"><?php echo $this->get_label('category'); ?></td>
  <td width="30%"><?php echo $this->get_label('actions'); ?></td>
  </tr>
<?php 
$res=$this->get_result('res');

if(count($res)==0) {?>
<tr class="data_table_message"><td colspan="4"><?php echo $this->get_label('no records found'); ?></td></tr>
<?php }else{ 


foreach($res as $key=>$row)
{
	?>
    <tr class="data_table_content">
    <td><?php echo $row['url'];?></td>
    <td><?php echo $this->get_ad_status($row['status']);?></td>
    <td><?php echo CategoryHelper::get_category_path($row['catid']);?></td>
    <td>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/7/".$row['id']."/".$category."/".$status."/".$page,BASE);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
    <a href="<?php echo $this->make_url("adunit/view/-1/".$row['id'],BASE);?>"><i class="fa fa-file-code-o adcode-icon" title="<?php echo $this->get_label('manage adunits'); ?>"></i></a>
     
    <a href="<?php echo $this->make_url("dispatch/category_targeting/8/".$row['id']."/".$category."/".$status."/".$page,BASE);?>" onclick="return confirm('<?php echo $this->get_message('site delete message');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a></td>
  </tr>
<?php 
} 
}
?>
</table>
<?php echo $this->get_variable('pagination');?>
<div style="height: 20px;"></div>
</div>