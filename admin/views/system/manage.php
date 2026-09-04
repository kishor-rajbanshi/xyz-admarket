<?php $this->dispatch("layout/header/3/_36");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage pages');?></div>

<?php $this->dispatch("links/links/46");?>

<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td style="width: 333px;"><?php echo $this->get_label('menu name');?></td>
<td style="width: 200px;"><?php echo $this->get_label('status');?></td>
<td style="width: 200px;"><?php echo $this->get_label('priority');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>


<tr><td colspan="4"></td></tr>
<?php 
$res=$this->get_result('res');


if(count($res)==0){?>
<tr><td colspan="4" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }

$i=1;
foreach($res as $key=>$value)
{
	$tid=$value['id'];
	
	?>
<tr class="row_data_tr">

<td ><?php echo $value['name'];?></td>
<td class="<?php if($value['status']==0){ ?>blk<?php }else if($value['status']==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($value['status']);?></td>

<td >


 <?php 
    if(count($res) >1) {
    if($i ==1) {?>
    <a href="<?php echo $this->make_url("system/change_page_priority/".$tid."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php } else if($i ==count($res)) { ?>
    <a href="<?php echo $this->make_url("system/change_page_priority/".$tid."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    <?php }else { ?>
    <a href="<?php echo $this->make_url("system/change_page_priority/".$tid."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    &nbsp;&nbsp;
    <a href="<?php echo $this->make_url("system/change_page_priority/".$tid."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php  }
    }
    else
    {
    	echo $this->get_label('na');
    }
    ?>





</td>
<td >
<a href="<?php echo $this->make_url("system/edit/".$tid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<?php if($value['status']==0){?>
<a href="<?php echo $this->make_url("system/change_page_status/".$tid."/1");?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php }else if($value['status']==1){?>
<a href="<?php echo $this->make_url("system/change_page_status/".$tid."/0");?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("system/delete/".$tid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this page');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php 
$i=$i+1;
}?>
<tr><td align="center" colspan="4"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<?php $this->dispatch("layout/footer");?>