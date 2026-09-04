<?php $this->dispatch("layout/header/3/_38");?>
<div class="sub_menu_main"><?php echo $this->get_label('manage notifications');?></div>

<?php $this->dispatch("links/links/60");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="3"> 
  
<?php 
$type=$this->get_variable('type');
$status=$this->get_variable('status');
$pg=$this->get_variable('pg');

$form=$this->create_form();
$form->start("managenotifications","","post");
?>
<div class="search_div">
<table class="search_div_table">
  <tr>
    <td style="width: 150px;">
    <select name="type" id="type" style="width: 140px;">
    <option value="-1" <?php if($type==-1) echo "selected"; ?>><?php echo $this->get_label('notifications for all');?></option>
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('advertisers');?></option>
    <option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('publishers');?></option>
    <option value="2" <?php if($type==2) echo "selected"; ?>><?php echo $this->get_label('adv & pub');?></option>
    <option value="3" <?php if($type==3) echo "selected"; ?>><?php echo $this->get_label('visitors');?></option>
    </select>
    </td>
    <td style="width: 120px;">
    <select name="status" id="status">
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
    </td>
    <td>&nbsp;<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
  </tr>
  </table>
  </div>
 <?php $form->end(); ?> 

</td></tr>

<tr><td colspan="3" style="height: 10px;"></td></tr>

</table>



<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">

<tr class="row_heading_tr">
<td style="width: 180px;"><?php echo $this->get_label('notifications for');?></td>
<td style="width: 120px;"><?php echo $this->get_label('status');?></td>
<td style="width: 150px;"><?php echo $this->get_label('notification expiry');?></td>
<td style="width: 300px;"><?php echo $this->get_label('message');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>

<?php 
$res=$this->get_result('res');

if(count($res)==0){?>
<tr><td colspan="5" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }
$i=1;
foreach($res as $key=>$value)
{
	$id=$value['id'];
	
	?>

<tr class="row_data_tr">
<td><?php if($value['type']==0){ echo $this->get_label('advertiser'); }else if($value['type']==1){ echo $this->get_label('publisher'); }else if($value['type']==2){ echo $this->get_label('adv & pub'); }else{ echo $this->get_label('visitors');}?></td>
<td class="<?php if($value['status']==0){ ?>blk<?php }else if($value['status']==1){ ?>active<?php }?>"><?php echo $this->get_ad_status($value['status']);?></td>


<td><?php echo $this->get_date_format(2,$value['time']);?></td>


<td class="ad-popup">
<?php echo substr($value['message'],0,100);?>
<div class="ad-popup-div">
<?php echo $value['message'];?>
</div>
</td>
<td>
<a href="<?php echo $this->make_url("system/edit_notifications/".$id."/".$type."/".$status."/".$pg);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<?php if($value['status']==0){?>
<a href="<?php echo $this->make_url("system/notification_status/".$id."/1/".$type."/".$status."/".$pg);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php }else if($value['status']==1){?>
<a href="<?php echo $this->make_url("system/notification_status/".$id."/0/".$type."/".$status."/".$pg);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("system/delete_notifications/".$id."/".$type."/".$status."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this notification');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>

</tr>
<?php
$i=$i+1;

}?>
<tr><td colspan="5" align="center"><?php echo $this->get_variable('pagination');?></td></tr>

</table>
<?php $this->dispatch("layout/footer");?>