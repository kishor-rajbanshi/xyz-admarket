<?php
$this->dispatch("layout/header/19");

$language_enabled=$this->get_variable('language_enabled');

if($language_enabled ==1)
$localedata=$this->get_result('localedata');
else
$localedata=array();

$localedatacount=count($localedata);

?>
<script src="<?php echo BASE;?>library/ckeditor/ckeditor.js"></script>
<script>
window.onload = function() {
	CKEDITOR.replace( 'message', {
		filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
		filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
		allowedContent: true,

	   	});
	<?php
			if($language_enabled ==1 && $localedatacount >0)
			{
				foreach($localedata as $key1=>$value1){?>
				CKEDITOR.replace('<?php echo 'message_'.$value1['id'];?>', {
					filebrowserBrowseUrl: '<?php echo $this->make_url("ckeditor/browse");?>',
					filebrowserUploadUrl: '<?php echo $this->make_url("ckeditor/upload");?>',
					allowedContent: true,
				   	});

				<?php }
			}?>

};
</script>

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

<style type="text/css">
iframe {width:99% !important;}
</style>
<?php

$id=$this->get_variable("id");


$form=$this->create_form();
$form->start("edit",$this->make_url("email_template/edit/").$id,"post");
$res1=$this->get_result('res1');
$subject=$this->get_variable("subject");
?>

<div class="sub_menu_main"><?php echo $this->get_label('edit email templates');?></div>

<?php $this->dispatch("links/links/22");?>





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
  	 <td></td>
  	 <td style="text-align: right;"><?php echo $this->get_label('compulsory message');?>&nbsp;</td>
  	 </tr>

	  <tr>
	  <td style="width: 120px;"><?php echo $this->get_label('subject');?></td>
	  <td><input type="text" name="subject" value="<?php echo $subject;?>" size="54"></td><td><span class="compulsory">*</span></td>
	  </tr>

	  <tr><td colspan="2" style="height: 10px;"></td></tr>

	  <tr>
	  <td><?php echo $this->get_label('message');?></td>
	  <td>
	  <textarea name="message" id="message" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable('message');?></textarea>
	  </td>
	  <td><span class="compulsory">*</span></td>
	  </tr>

	  <tr>
  	  <td></td>
	  <td style="height:50px;">
	  <?php if(!DEMO_MODE) {?>
	  <input type="submit" name="submit" value="<?php echo $this->get_label('update');?>" />
	  <?php }?>
	  </td>
	  </tr>
	</table>
	</td>
	</tr>

<?php if($language_enabled ==1 && $localedatacount >0)
{
	foreach($localedata as $key1=>$value1){?>
	<tr id="showtab<?php echo $value1['id'];?>" class="tabcontentclass">
	<td colspan="<?php echo $localedatacount+2;?>" class="tab-border">

	<table style="width: 100%" cellpadding="0" cellspacing="0">
	<tr>
	<td style="width: 120px;"><?php echo $this->get_label('subject');?></td>
	<td ><input type="text" name="subject_<?php echo $value1['id'];?>" id="subject_<?php echo $value1['id'];?>" value="<?php echo $this->get_variable($value1['id'].'_subject');?>" size="54"></td><td></td>
	</tr>

	<tr><td colspan="2" style="height: 10px;"></td></tr>

	<tr>
	<td><?php echo $this->get_label('message');?></td>
	<td>
	 <textarea name="<?php echo 'message_'.$value1['id'];?>" id="<?php echo 'message_'.$value1['id']; ?>" style="width: 90%;margin-top: 5px;"><?php echo $this->get_variable($value1['id'].'_message');?></textarea>

	</td>
	<td></td>
	</tr>

	  <tr>
  	  <td></td>
	  <td style="height:50px;">
	  <?php if(!DEMO_MODE) {?>
	  <input type="submit" name="submit_<?php echo $value1['id'];?>" value="<?php echo $this->get_label('update');?>" />
	  <?php }?>
	  </td>
	  </tr>
	</table>
	</td>
	</tr>
<?php }}?>
</table>

<?php $form->end(); ?>
<script type="text/javascript">
show_tab(0);
</script>
<?php $this->dispatch("layout/footer");?>
