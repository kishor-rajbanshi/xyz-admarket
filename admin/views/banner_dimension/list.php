<?php 
$this->dispatch("layout/header/4/_44");
$status=$this->get_variable("status");
$pg=$this->get_variable("pg");


$device_enabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage banner dimensions');?></div>

<?php $this->dispatch("links/links/19");?>

<table  style="width:100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="5">
<?php 
$form=$this->create_form();
$form->start("managedimension",$this->make_url("banner_dimension/list"),"post");
?>
   <div class="search_div">
<table class="search_div_table">
<tr><td colspan="5">
    <select name="status" id="status">
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
 
   &nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" />
 
</td></tr>
  </table>
  </div>
 <?php $form->end(); ?> 
 </td></tr>
<tr><td colspan="5" height="10px"></td></tr>

<tr><td colspan="5">

<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">


<td><?php echo $this->get_label('width');?> (<?php echo $this->get_label('px');?>)</td>
<td><?php echo $this->get_label('height');?> (<?php echo $this->get_label('px');?>)</td>
<td><?php echo $this->get_label('filesize');?> (<?php echo $this->get_label('kb');?>)</td>

<?php if($interstitial_enabled ==1 || $textimage_enabled ==1){?>
<td><?php echo $this->get_label('banner type');?></td>
<?php }?>


<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('action');?></td>
</tr>

<?php 
$res=$this->get_result('res');
if(count($res)==0){?>
<tr><td colspan="7" height="30px"><?php echo $this->get_label('no records found');?></td></tr>
<?php 
} else {

foreach($res as $key=>$value)
{
	$bid=$value['id'];
	?>
<tr class="row_data_tr">
<td ><?php echo $value['width'];?> </td>
<td ><?php echo $value['height'];?> </td>
<td >
<?php echo $value['filesize'];?> 
</td>
<?php if($interstitial_enabled ==1 || $textimage_enabled ==1){?>
<td >
<?php 
if($value['banner_type'] ==0)
echo $this->get_label('normal ads');
else if($value['banner_type'] ==1)
echo $this->get_label('interstitial ads');
else if($value['banner_type'] ==3)
echo $this->get_label('textimage ads');
else if($value['banner_type'] ==4)
echo $this->get_label('skin ads');
?>
</td>
<?php }?>

<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_banner_status($value['status']);?> </td>
<td >

 <a href="<?php echo $this->make_url("banner_dimension/edit/".$bid."/".$status."/".$pg);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
 
 <?php if($value['status']==-1) {?>

 <a href="<?php echo $this->make_url("banner_dimension/change_status/".$bid."/1/".$status."/".$pg);?>" ><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
 
 <a href="<?php echo $this->make_url("banner_dimension/change_status/".$bid."/2/".$status."/".$pg);?>" ><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
 <?php }?>
 
  <?php if($value['status']==2) {?>
 <a href="<?php echo $this->make_url("banner_dimension/change_status/".$bid."/1/".$status."/".$pg);?>" ><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
 <?php }?>
 
  <?php if($value['status']==1) {?>
 <a href="<?php echo $this->make_url("banner_dimension/change_status/".$bid."/2/".$status."/".$pg);?>" ><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
  <?php }?>

 <a href="<?php echo $this->make_url("banner_dimension/delete/".$bid."/".$status."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this banner dimension');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php }?>	
<tr><td colspan="7" align="center"><?php echo $this->get_variable('pagination');?></td></tr>
<?php }?>
</table>
</td>
</tr>
</table>
<?php $this->dispatch("layout/footer");?>	