
<div class="sub_menu_main"><?php echo $this->get_label('manage categories');?>&nbsp;
<a href="<?php echo $this->make_url("dispatch/category_targeting/2/0",ADMIN_DIR); ?>"><?php echo $this->get_label('root'); ?></a> &raquo; <?php echo $this->get_variable('categorypath'); ?>
</div> 

<?php $this->dispatch("links/links/30");?>

<div class="inner-box">
<div class="profile_div">
<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

  <tr class="row_heading_tr">
    <td width="40%"><?php echo $this->get_label('category name'); ?></td>
    <td width="30%"><?php echo $this->get_label('actions'); ?></td>
  </tr>
    

<?php 
$res=$this->get_result('res');
foreach($res as $key=>$row)
{
	$child_count=CategoryHelper::get_category_child_count($row['id']);
	?>
    <tr class="row_data_tr">
    <td>
    <?php if($child_count >0){
    ?>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/2/".$row['id'],ADMIN_DIR);?>"><?php echo $row['name']; ?></a> (<?php echo $child_count;?>)
    <?php } else {   echo $row['name']." (".$child_count.")";  }
    ?>
    </td>
    <td>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/1/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('add child'); ?></a>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/3/".$row['id'],ADMIN_DIR);?>" class="link_button"><?php echo $this->get_label('edit'); ?></a>
    <a href="<?php echo $this->make_url("dispatch/category_targeting/4/".$row['id'],ADMIN_DIR);?>" class="link_button" onclick="return confirm('<?php echo $this->get_message('category delete message');?>');"><?php echo $this->get_label('delete'); ?></a> </td>
  </tr>
<?php 
} 
?>
<?php if(count($res)==0) {?>
  <tr class="row_data_tr"><td colspan="3"><?php echo $this->get_label('no record'); ?></td></tr>
<?php } ?>
</table>
</div> 
</div>