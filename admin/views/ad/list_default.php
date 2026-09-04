<?php $this->dispatch("layout/header/1/_12");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage default ads');?></div>

<?php $this->dispatch("links/links/15");?>

<table style="width: 100%;" cellpadding="0" cellspacing="0" >
<tr><td>
<?php 

$pop_enabled=$this->get_variable('pop_enabled');
$cpv_enabled=$this->get_variable('cpv_enabled');
$textimage_enabled=$this->get_addon_status('text-image-ads_enabled');
$interstitial_enabled=$this->get_addon_status('interstitial_enabled');
$skin_enabled=$this->get_addon_status('skin-ads_enabled');

$text_ads_enabled=$this->get_variable('text_ads_enabled');


$type=$this->get_variable('type');
$status=$this->get_variable('status');
$pg=$this->get_variable("pg");

$form=$this->create_form();
$form->start("manageads",$this->make_url("ad/list_default"),"post");
?>
     <div class="search_div">
<table class="search_div_table">
  <tr>
  <td style="width: 120px;">
    <select name="type" id="type">
    <option value="0" <?php if($type ==0) echo "selected"; ?>><?php echo $this->get_label('allads');?></option>
    
    <?php if($text_ads_enabled ==1){?>
    <option value="1" <?php if($type ==1) echo "selected"; ?>><?php echo $this->get_label('textad');?></option>
    <?php }?>
    
    <option value="2" <?php if($type ==2) echo "selected"; ?>><?php echo $this->get_label('bannerad');?></option>
    
	<?php if($interstitial_enabled ==1){?> 
    <option value="5" <?php if($type ==5) {echo "selected";}?>><?php echo $this->get_label('interstitial ad');?></option>
    <?php }?>    

	<?php if($textimage_enabled ==1){?>
    <option value="11" <?php if($type ==11) {echo "selected";}?>><?php echo $this->get_label('textimage ad');?></option>
    <?php }?>    
    
    <?php if($pop_enabled ==1){?>
	<option value="9" <?php if($type ==9){ echo "selected"; } ?>><?php echo $this->get_label('popad');?></option>
	<?php }?>
	
    <?php if($cpv_enabled ==1){?>
	<option value="13" <?php if($type ==13){ echo "selected"; } ?>><?php echo $this->get_label('video ad');?></option>
	<?php }?>	
	
    <?php if($skin_enabled ==1){?>
    <option value="14" <?php if($type ==14) {echo "selected";}?>><?php echo $this->get_label('skin ad');?></option>
    <?php }?>

    </select>
    </td>
    <td style="width: 120px;">
    <select name="status" id="status">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>
    </td>
    <td>&nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
    
    
    
  </tr>
  </table></div>
 <?php $form->end(); ?> 
</td></tr>


<tr><td height="10px" colspan="3"></td></tr>


</table>







<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">



<tr class="row_heading_tr">
<td><?php echo $this->get_label('id');?></td>
<td><?php echo $this->get_label('name');?></td>
<td><?php echo $this->get_label('type');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('options');?></td>
</tr>


<tr><td colspan="5"></td></tr>
<?php 
$res=$this->get_result('res');


if(count($res)==0){?>
<tr><td colspan="5" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }

foreach($res as $key=>$value)
{
	$aid=$value['id'];
	
	?>
<tr class="row_data_tr">
<td><?php echo $aid;?></td>
<td class="ad-popup">
<a href="<?php echo $this->make_url("ad/view_default/".$aid);?>"><?php echo $value['name'];?></a>


<?php if($value['type'] !=0 && $value['type'] !=14){?>
<div class="ad-popup-div">
<?php if($value['type'] ==1 || $value['type'] ==11){?>
<table style="width: 100%;">
<tr>
<?php if($value['type'] ==11){?>
<td style="vertical-align: middle;width: <?php echo $val1['width'];?>px;padding-right: 2px;border-bottom: 0px;">
<img alt="<?php echo $this->get_label('banner');?>" src="<?php echo '../'.DATA_DIR;?>/<?php echo $aid;?>_<?php echo $value['banner'];?>" />
</td>
<?php }?>

<td style="border-bottom: 0px;">
<div class="title"><?php echo $value['title'];?></div>
<div class="description"><?php echo $value['description'];?></div>
<div class="url"><?php echo $value['display_url'];?></div>
</td>
</tr> 
</table>


<?php }else if($value['type'] ==2 || $value['type'] ==5){?>

<img style="max-width:700px;" alt="<?php echo $this->get_label('banner');?>" src="../<?php echo DATA_DIR;?>/<?php echo $aid;?>_<?php echo $value['banner'];?>" />


<?php }else if($value['type'] ==13){?>

<video width="300" height="225" controls >
<source src="<?php echo '../'.DATA_DIR.'/video/'.$value['id'].'/'.$value['banner'];?>" type="<?php echo $value['mime_type'];?>"></source>
<?php echo $this->get_label('your browser does not support HTML5 video');?>
</video>

<?php }?>

</div>
<?php }?>

</td>
<td >
<?php echo $this->get_ad_type($value['type'],$value['display_type']);?>
<?php 
if($value['type'] ==13)
{
	$aspect_ratio=$this->get_aspect_ratio_value($value['aspect_ratio']);
	
	if($aspect_ratio >0)
	echo '<div style="margin-top:5px;">'.$this->get_label('aspect ratio').' '.$aspect_ratio.'</div>';
}
?>
</td>
<td class="<?php if($value['status']==0){ ?>blk<?php }
    else if($value['status']==-1){ ?>pend<?php }
	else if($value['status']==1){ ?>active<?php }?>"


><?php echo $this->get_ad_status($value['status']);?></td>

<td >
<a href="<?php echo $this->make_url("ad/edit_default/".$aid."/1/".$type."/".$status."/".$pg);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>


<?php if($value['status'] ==-1 || $value['status'] ==0){ ?>
 <a href="<?php echo $this->make_url("ad/change_status_default/".$aid."/1/1/".$type."/".$status."/".$pg);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate ad');?>"></i></a>
<?php }

if($value['status'] ==1){?>
 <a href="<?php echo $this->make_url("ad/change_status_default/".$aid."/0/1/".$type."/".$status."/".$pg);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block ad');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("ad/delete_default/".$aid."/".$type."/".$status."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this default ad');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php }?>
<tr><td align="center" colspan="5"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<?php $this->dispatch("layout/footer");?>	