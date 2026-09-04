<?php $this->dispatch("layout/header/3/_40");?>

<div class="sub_menu_main"><?php echo $this->get_label('manage faq');?></div>

<?php $this->dispatch("links/links/49");?>


<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="3">

<?php
$type=$this->get_variable('type');
$pg=$this->get_variable('pg');

$form=$this->create_form();
$form->start("manage_faq","","post");
?>
<div class="search_div">
<table class="search_div_table">
  <tr>
    <td style="width: 150px;">
    <select name="type" id="type" style="width: 140px;">
    <option value="0" <?php if($type==0) echo "selected"; ?>><?php echo $this->get_label('advertisers');?></option>
    <option value="1" <?php if($type==1) echo "selected"; ?>><?php echo $this->get_label('publishers');?></option>
    </select>
    </td>
    <td>&nbsp;<input type="submit" name="search" value="<?php echo $this->get_label('go');?>" /></td>
  </tr>
  </table>
  </div>
 <?php
 $form->end();
 ?>

</td></tr>

<tr><td colspan="3" style="height: 10px;"></td></tr>

</table>


<table  style="width: 100%;" class="data_table" cellpadding="0" cellspacing="0">
<tr class="row_heading_tr">
    <td style="width: 10%;"><?php echo $this->get_label('id'); ?></td>
    <td style="width: 15%;"><?php echo $this->get_label('user type'); ?></td>
    <td style="width: 35%;"><?php echo $this->get_label('question'); ?></td>
		<td style="width: 10%;"><?php echo $this->get_label('priority'); ?></td>
		<td style="width: 15%;"><?php echo $this->get_label('status'); ?></td>
    <td style="width: 15%;"><?php echo $this->get_label('options'); ?></td>
</tr>

<tr><td colspan="6"></td></tr>
<?php
$res=$this->get_result('faq_data');


if(count($res)==0){?>
<tr><td colspan="6" height="30px">&nbsp;<?php echo $this->get_label('no records found');?></td></tr>
<?php }

$i=1;
foreach($res as $key=>$value)
{
	$tid=$value['id'];

	?>
<tr class="row_data_tr">
<td ><?php echo $value['id'];?></td>
<td> <?php if($value['type']==0){ echo $this->get_label('advertiser'); }else if($value['type']==1){  echo $this->get_label('publisher'); }?></td>
<td>
  <?php
    $question = $value['question'];
    $max_length = 100;

    if(strlen($question) > $max_length)
      $question_title="title='".$question."'";
    else
      $question_title = "";
  ?>
  <span <?php echo $question_title; ?>>
    <?php
      if(strlen($question) > $max_length)
          echo substr($question, 0, $max_length) . '...';
      else
          echo $question;
      ?>
  </span>
</td>
<td >
 <?php
    if(count($res) >1) {
    if($i ==1) {?>
    <a href="<?php echo $this->make_url("system/change_faq_priority/".$tid."/".$value['type']."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php } else if($i ==count($res)) { ?>
    <a href="<?php echo $this->make_url("system/change_faq_priority/".$tid."/".$value['type']."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    <?php }else { ?>
    <a href="<?php echo $this->make_url("system/change_faq_priority/".$tid."/".$value['type']."/1");?>"><i class="fa fa-arrow-up" title="<?php echo $this->get_label('up');?>"></i></a>
    &nbsp;&nbsp;
    <a href="<?php echo $this->make_url("system/change_faq_priority/".$tid."/".$value['type']."/0");?>"><i class="fa fa-arrow-down" title="<?php echo $this->get_label('down');?>"></i></a>
    <?php  }
    }
    else
    {
    	echo $this->get_label('na');
    }
    ?>
</td>
<td class="<?php if($value['status']==0){ ?>blk<?php }else if($value['status']==1){ ?>active<?php }?>"><?php echo $value['status']==0 ? $this->get_label('blocked') : $this->get_label('active');?></td>
<td>
	<a href="<?php echo $this->make_url("system/edit_faq/".$tid);?>"><i class="fa fa-edit edit-icon" title="<?php echo $this->get_label('edit');?>"></i></a>
<?php if($value['status']==0){?>
<a href="<?php echo $this->make_url("system/change_faq_status/".$tid."/".$value['type']."/1");?>"><i class="fa fa-check-circle-o approve-icon" title="<?php echo $this->get_label('activate');?>"></i></a>
<?php }else if($value['status']==1){?>
<a href="<?php echo $this->make_url("system/change_faq_status/".$tid."/".$value['type']."/0");?>"><i class="fa fa-times-circle-o block-icon" title="<?php echo $this->get_label('block');?>"></i></a>
<?php }?>

<a href="<?php echo $this->make_url("system/delete_faq/".$tid);?>" onclick="return confirm('<?php echo $this->get_message('do you really want to delete this faq');?>')"><i class="fa fa-trash delete-icon" title="<?php echo $this->get_label('delete');?>"></i></a>
</td>
</tr>
<?php
$i=$i+1;
}?>
<tr><td align="center" colspan="5"><?php echo $this->get_variable('pagination');?></td></tr>
</table>
<?php $this->dispatch("layout/footer");?>
