<?php $this->dispatch("layout/header/3/_37");?>
<div class="sub_menu_main"><?php echo $this->get_label('manage custom banner');?></div>

<?php $this->dispatch("links/links/59");?>

<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

<tr class="row_heading_tr">
<td style="width: 200px;"><?php echo $this->get_label('banner title');?></td>
<td style="width: 150px;"><?php echo $this->get_label('status');?></td>
<td style="width: 100px;"><?php echo $this->get_label('banner for');?></td>
<td style="width: 250px;"><?php echo $this->get_label('banner');?></td>
<td style="width: 100px;"><?php echo $this->get_label('priority');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>

<?php 
$res=$this->get_result('res');

if(count($res)==0){?>
<tr><td colspan="6" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }
$i=1;
foreach($res as $key=>$value)
{
	$id=$value['id'];
	
	?>

<tr class="row_data_tr">

<td class="ad-popup"><?php echo $this->escape($value['title']);?>
<div class="ad-popup-div"><?php echo $value['content'];?></div>
</td>




<td class="<?php if($value['status']==0){ ?>blk<?php }else if($value['status']==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($value['status']);?></td>


<td><?php if($value['type']==1){ echo $this->get_label('advertiser'); }else if($value['type']==2){ echo $this->get_label('publisher'); }else{ echo $this->get_label('all');}?></td>



<td class="ad-popup">
<img style="margin: 5px;max-width: 150px;max-height: 50px" alt="<?php echo $this->get_label('custom banner');?>" title="<?php echo $this->get_label('custom banner');?>" src="<?php echo '../'.DATA_DIR.'/logo/'.$value['banner'];?>" />

<div class="ad-popup-div">
<img style="max-width:700px;" alt="<?php echo $this->get_label('custom banner');?>" src="<?php echo '../'.DATA_DIR.'/logo/'.$value['banner'];?>" />
</div>


</td>

<td>

    <?php 
    if(count($res) >1) {
    if($i ==1) {?>
    <a href="<?php echo $this->make_url("system/change_priority/".$id."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php } else if($i ==count($res)) { ?>
    <a href="<?php echo $this->make_url("system/change_priority/".$id."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    <?php }else { ?>
    <a href="<?php echo $this->make_url("system/change_priority/".$id."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    &nbsp;&nbsp;
    <a href="<?php echo $this->make_url("system/change_priority/".$id."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php
    }
    }
    else
    {
    	echo $this->get_label('na');
    }
    
    
    
    
    ?>




</td>


<td >
<a href="<?php echo $this->make_url("system/edit_banner/".$id);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<?php if($value['status']==0){?>
<a href="<?php echo $this->make_url("system/change_status/".$id."/1");?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php }else if($value['status']==1){?>
<a href="<?php echo $this->make_url("system/change_status/".$id."/0");?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("system/delete_banner/".$id);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this banner');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>

</tr>
<?php
$i=$i+1;

}?>
</table>
<?php $this->dispatch("layout/footer");?>