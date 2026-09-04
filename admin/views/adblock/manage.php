<?php 

$this->dispatch("layout/header/4/_42");

$res=$this->get_result('res');


$device_enabled=$this->get_addon_status('device-targeting_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');

$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$video_enabled=$this->get_addon_status('video-ads_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage adblocks');?></div>

<?php $this->dispatch("links/links/18");?>


<table  class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
<td ><?php echo $this->get_label('adblock name');?></td>
<td ><?php echo $this->get_label('dimension');?></td>
<td ><?php echo $this->get_label('adtype');?></td>

<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
<td><?php echo $this->get_label('adblock type');?></td>
<?php }?>

<td ><?php echo $this->get_label('Status');?></td>
<td style="width: 170px;"><?php echo $this->get_label('options');?></td>

</tr>
<?php 

if(count($res) >0)
{

	$textimageData = 0;
	
foreach($res as $key=>$value)
{
	$adbid=$value['id'];
	
	if($textimage_enabled == 1 && $value['textimage_size'] > 0)
	$textimageData	= 1;
	else
	$textimageData  = 0;		
	
?>
<tr class="row_data_tr">
<td ><?php echo $value['name'];?></td>
<td >
<?php 
if($value['banner_type'] == 4)
echo $this->get_label('na');
else
echo $value['width']." x ".$value['height'];
?>
</td>
<td ><?php echo $this->get_adblock_type($value['type'],0,$textimageData);?> </td>

<?php if($interstitial_enabled ==1 || $skin_enabled ==1){?>
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
else if($value['banner_type'] ==5)
echo $this->get_label('video ads');
?>
</td>
<?php }?>


<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"><?php echo $this->get_adblock_status($value['status']);?></td>


<td ><a href="<?php echo $this->make_url("adblock/edit/".$adbid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>

<a href="<?php echo $this->make_url("adblock/delete/".$adbid);?>" onclick="return confirm('<?php echo $this->get_message('adblock delete alert');?>');"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
<?php if($value['status']==1){?>
<a href="<?php echo $this->make_url("adblock/block/".$adbid);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>
<?php if($value['status']==-1 || $value['status']==0){?>

<a href="<?php echo $this->make_url("adblock/activate/".$adbid);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>

<?php }?>
</td></tr>
<?php }}else{?>	

<tr><td colspan="6" height="30px"><?php echo $this->get_label("no records found");?></td></tr>


<?php }?>
</table>
<?php $this->dispatch("layout/footer");?>		