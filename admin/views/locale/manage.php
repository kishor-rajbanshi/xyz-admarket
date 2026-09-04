<?php 
$this->dispatch("layout/header/3/_39");

$res=$this->get_result('res');
?>
<div class="sub_menu_main"><?php echo $this->get_label('manage locale');?></div>

<?php $this->dispatch("links/links/55");?>


<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

<tr class="row_heading_tr">
<td ><?php echo $this->get_label('locale name'); ?></td>
<td ><?php echo $this->get_label('locale desc'); ?></td>
<td style="width: 200px;"><?php echo $this->get_label('status'); ?></td>
<td ><?php echo $this->get_label('direction'); ?></td>
<td style="width: 200px;"><?php echo $this->get_label('actions'); ?></td>
</tr>
<?php 
foreach($res as $row)
{
?>
  <tr class="row_data_tr">
  <td ><?php echo $row['name']; ?></td>
  <td ><?php echo $row['description']; ?></td>
  <td class="<?php if($row['status'] ==0){ ?>blk<?php }else if($row['status'] ==1){ ?>active<?php }?>"><?php if($row['status'] ==1)echo $this->get_label('active');else echo $this->get_label('blocked'); ?></td>
  <td ><?php if($row['direction'] ==0) {echo $this->get_label('ltr');}else if($row['direction'] ==1) {echo $this->get_label('rtl');} ?></td>
  <td >
    <?php 
    if(DEFAULT_LOCALE==$row['name'])
    echo $this->get_label('default locale');
    else
    {?>
    <a href="<?php echo $this->make_base_url('locale/edit/'.$row['id'],ADMIN_DIR);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a> 

	<?php if($row['status'] ==1){?>
	<a href="<?php echo $this->make_base_url("locale/status/".$row['id']."/0",ADMIN_DIR);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
	<?php }else{?>
	<a href="<?php echo $this->make_base_url("locale/status/".$row['id']."/1",ADMIN_DIR);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
	<?php }?>

    <a href="<?php echo $this->make_base_url('locale/delete/'.$row['id'],ADMIN_DIR);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this locale');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
    <?php } ?> 
  </td>
  </tr>
  <?php } ?>
  <?php 
  if(count($res) ==0){?><tr><td colspan="5" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
  <?php } ?>
</table>
<?php $this->dispatch("layout/footer");?>