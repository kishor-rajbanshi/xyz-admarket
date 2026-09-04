<?php 
$this->dispatch("layout/header/18");
$status=$this->get_variable('status');
$pg=$this->get_variable("pg");
?>

<div class="sub_menu_main"><?php echo $this->get_label('manage keywords');?></div>

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="3">
<?php 
$form=$this->create_form();
$form->start("managekeywords",$this->make_url("keyword/list"),"post");
?>
     <div class="search_div">
<table class="search_div_table">
  <tr>
  
    <td colspan="3">
    <select name="status" id="status">
    <option value="2" <?php if($status==2) echo "selected"; ?>><?php echo $this->get_label('allstatus');?></option>
    <option value="1" <?php if($status==1) echo "selected"; ?>><?php echo $this->get_label('active');?></option>
    <option value="-1" <?php if($status==-1) echo "selected"; ?>><?php echo $this->get_label('pending');?></option>
    <option value="0" <?php if($status==0) echo "selected"; ?>><?php echo $this->get_label('blocked');?></option>
    </select>

    &nbsp;&nbsp; <input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
  </tr>
  </table></div>
 <?php $form->end(); ?> 
 
</td></tr>


<tr><td colspan="3" height="10px"></td></tr>

<tr><td colspan="3">
<table class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">



<td width="250px"><?php echo $this->get_label('keyword');?></td>
<td><?php echo $this->get_label('status');?></td>
<td><?php echo $this->get_label('action');?></td>

</tr>
<tr><td colspan="3"></td></tr>
<?php 
$res=$this->get_result('res');
if(count($res)==0)
{
?>
<tr class="row_data_tr"><td colspan="3" height="30px"><?php echo $this->get_label('no keyword found');?></td></tr>	
<?php 	
}




foreach($res as $key=>$value)
{
	$kid=$value['id'];
	$dbstatus=$value['status'];
	
	?>
<tr class="row_data_tr">
<td ><?php echo $value['keyword'];?></td>
<td class="<?php if($value['status'] ==0){ ?>blk<?php }
    else if($value['status'] ==-1){ ?>pend<?php }
	else if($value['status'] ==1){ ?>active<?php }?>"




><?php echo $this->get_keyword_status($value['status']); ?></td>
<td >


<?php if($dbstatus ==1){?>
<a href="<?php echo $this->make_url("keyword/change_status/".$kid."/0/".$status."/".$pg);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }else if($dbstatus ==-1){?>
<a href="<?php echo $this->make_url("keyword/change_status/".$kid."/1/".$status."/".$pg);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>

<a href="<?php echo $this->make_url("keyword/change_status/".$kid."/0/".$status."/".$pg);?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php } else if($dbstatus ==0){?>
<a href="<?php echo $this->make_url("keyword/change_status/".$kid."/1/".$status."/".$pg);?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php } ?>

<a href="<?php echo $this->make_url("keyword/delete/".$kid."/".$status."/".$pg);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this keyword');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php }?>		
<tr><td align="center" colspan="3"><?php echo $this->get_variable('pagination');?></td></tr>

</table>
</td></tr>
</table>
<?php $this->dispatch("layout/footer");?>