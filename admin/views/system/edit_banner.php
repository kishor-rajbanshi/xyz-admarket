<?php
$this->dispatch("layout/header/3/_37");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);

$validate=array(
		"title"=>array(
				"notNull"=>array($this->get_message("not null"))
		),	
		"content"=>array(
				"notNull"=>array($this->get_message("not null"))
		)
);

$title=$this->get_variable("title");
$content=$this->get_variable("content");
$clogo=$this->get_variable("clogo");
$id=$this->get_variable("id");
$type=$this->get_variable("type");

?>
<script type="text/javascript">
function show_tab(tab)
{
	$('.tabcontentclass').hide();

	$('.tabclass').removeClass('tab-selection');
	
	$('#showtab'+tab).show();
	$('#tb'+tab).addClass('tab-selection');

	$('#tab'+tab).val(tab);
}
</script>

<div class="sub_menu_main"><?php echo $this->get_label('edit custom banner');?></div>

<?php $this->dispatch("links/links/59");?>


<div class="inner-box">
<div class="pages-input">

<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr><td colspan="2">
<?php 
$form=$this->create_form();
$form->start("edit_banner","","post",$validate);
?>
<table style="width: 100%;" cellpadding="0" cellspacing="0">
<tr>
<td></td>
<td><?php echo $this->get_label('compulsory message');?></td>
</tr>

<tr>
<td style="width: 125px;"><?php echo $this->get_label('banner for');?></td>
<td style="width: 350px;">
<select name="type" id="type">
<option value="0" <?php if($type ==0){?>selected<?php }?>><?php echo $this->get_label('select');?></option>
<option value="1" <?php if($type ==1){?>selected<?php }?>><?php echo $this->get_label('advertiser');?></option>
<option value="2" <?php if($type ==2){?>selected<?php }?>><?php echo $this->get_label('publisher');?></option>
</select>
</td>
<td rowspan="2">   
<img style="max-width:200px;max-height: 200px;" alt="<?php echo $this->get_label('custom banner');?>" src="<?php echo '../'.DATA_DIR.'/logo/'.$clogo;?>" />
</td>
</tr>


<tr>
<td><?php echo $this->get_label('banner image');?></td>
<td><input type="file" name="clogo" id="clogo" /><span class="compulsory">*</span><br/>
<span class="notification">[<?php echo $this->get_label('supported image format');?>]&nbsp;<br/>[<?php echo $this->get_label('custom banner size');?>]</span>
</td>
</tr>

<tr><td colspan="3" style="height: 30px;"></td></tr>

<tr>
<td colspan="3">

<table style="width: 100%;" class="inner-table" >
<tr class="statistics_header">

<td onclick="show_tab(0);"  id="tb0" class="tabclass" style="width: 120px;"><?php echo $this->get_label('default content');?></td>


<?php 
if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	 <td onclick="show_tab(<?php echo $value1['id'];?>);"  id="tb<?php echo $value1['id'];?>" class="tabclass" style="width: 100px;"><?php echo $value1['description']; ?></td>
<?php }}?>
  
<td class="tabclass"></td>
</tr>



 
	<tr id="showtab0" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">

	<tr>
	<td><?php echo $this->get_label('banner title');?></td>
	<td><input type="text" name="title" id="title" value="<?php echo $title;?>" /><span class="compulsory">*</span>
	<span class="notification">[<?php echo $this->get_label('maximum banner title characters');?>]</span>
	</td>
	</tr>
	
	<tr>
	<td><?php echo $this->get_label('banner content');?></td>
	<td><textarea name="content" id="content" rows="4" cols="50"><?php echo $content;?></textarea><span class="compulsory">*</span>
	<span class="notification">[<?php echo $this->get_label('maximum banner content characters');?>]</span>
	</td>
	</tr>
	
	<tr><td colspan="2" style="height: 30px;"></td></tr>
  
	</table>  
	</td>
	</tr>  
 

<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">
	
	<table style="width: 100%" cellpadding="0" cellspacing="0">
		
	  <tr >
	  <td><?php echo $this->get_label('banner title'); ?></td>
	  <td>  
  	  <input type="text" name="<?php echo $value1['id'];?>_title" id="<?php echo $value1['id'];?>_title" value="<?php echo $this->get_variable($value1['id'].'_title');?>" />
  	  <span class="notification">[<?php echo $this->get_label('maximum banner title characters');?>]</span>
	  </td>
	  </tr>
	  	  
	  <tr >
	  <td><?php echo $this->get_label('banner content'); ?></td>
	  <td>  
 	  <textarea name="<?php echo $value1['id'];?>_content" id="<?php echo $value1['id'];?>_content" rows="4" cols="50" ><?php echo $this->get_variable($value1['id'].'_content');?></textarea>
	  <span class="notification">[<?php echo $this->get_label('maximum banner content characters');?>]</span>
	  </td>
	  </tr>
	  
	  <tr><td colspan="2" style="height: 30px;"></td></tr>
	  
  
	</table>  
	</td>
	</tr>  
<?php }}?>
</table> 
</td>
</tr>

<tr>
<td colspan="3" style="text-align: center;">
<input type="hidden" name="id" id="id" value="<?php echo $id;?>" />
<input type="submit" name="submit" value="<?php echo $this->get_label('edit banner');?>">
</td>
</tr>
</table>
<?php $form->end(); ?>
</td></tr>
</table>

</div>
</div>
<script type="text/javascript">
show_tab(0);
</script>
<?php $this->dispatch("layout/footer");?>